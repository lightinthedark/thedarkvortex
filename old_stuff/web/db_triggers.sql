/* ~~~~~~~~~~~~~~~~~~~
   Trigger Definitions
   ~~~~~~~~~~~~~~~~~~~ */

DELIMITER //

/**
 * Before adding a row to the paths, we need to fill in all the calculated values
 */
DROP TRIGGER IF EXISTS beforeInsertPath//

CREATE TRIGGER beforeInsertPath BEFORE INSERT ON paths
FOR EACH ROW BEGIN
  /* Calculate all the vector info that we'll need */
  DECLARE _magnitude, _radius DOUBLE;
  
  SET NEW.i = (NEW.x2 - NEW.x1);
  SET NEW.j = (NEW.y2 - NEW.y1);
  CALL vMag( NEW.i, NEW.j, _magnitude );
  CALL vUnit( NEW.i, NEW.j, NEW.speed, _magnitude, NEW.i_unit, NEW.j_unit );
  
  /* Calculate start position / time as first set of check info */
  IF( NEW.time_start IS NULL OR NEW.time_start = 0 ) THEN
    SET NEW.time_start = UNIX_TIMESTAMP();
  END IF;
  IF( _magnitude = 0 ) THEN
    SET NEW.time_end = 4102444800; # 2100/01/01 00:00:00 If this needs changing, then hello great-grandson. Love :-Dave
  ELSE
    SET NEW.time_end = NEW.time_start + CEIL( _magnitude / NEW.speed );
  END IF;
  SET NEW.time_check = NEW.time_start;
  SET NEW.x_check = NEW.x1;
  SET NEW.y_check = NEW.y1;
  
  /* Calculate the extent of the path's potential effects */
  SELECT `radius`
  INTO _radius
  FROM `stacks`
  WHERE `id` = NEW.stack_id;
  
  IF NEW.x1 < NEW.x2 THEN
    SET NEW.x_min = NEW.x1 - _radius;
    SET NEW.x_max = NEW.x2 + _radius;
  ELSE
    SET NEW.x_min = NEW.x2 - _radius;
    SET NEW.x_max = NEW.x1 + _radius;
  END IF;

  IF NEW.y1 < NEW.y2 THEN
    SET NEW.y_min = NEW.y1 - _radius;
    SET NEW.y_max = NEW.y2 + _radius;
  ELSE
    SET NEW.y_min = NEW.y2 - _radius;
    SET NEW.y_max = NEW.y1 + _radius;
  END IF;
  
END;
//

DELIMITER ;

/* **** START HERE - This trigger function is still developing.
   Fairly confident in the accuracy of the rows it's putting in now.
   
   Speed of this needs serious work
   Speed of other trigger can't really be better I think. (23s for 10000 rows)
   I'm working on a pentium II 233 here, so it's never going to be quick,
   but quicker here means greased lightning on a real server
   
   We use the cursor and the loop to go through potential paths
   Apply various checks in order of expense
   If we've got a collision add it as an entry in the events table
   
   http://www.mysqltutorial.org/stored-procedures-parameters.aspx
*/
DELIMITER //
DROP TRIGGER IF EXISTS afterInsertPath//

CREATE TRIGGER afterInsertPath AFTER INSERT ON paths
FOR EACH ROW BEGIN
  /* Find which paths we get close to */
  DECLARE _id, _stack_id, _speed, _time_start, _time_end, _time_check INT;
  DECLARE _r_new, _r, _x1, _y1, _x2, _y2, _i, _j, _i_unit, _j_unit, _x_check, _y_check, _x_min, _y_min, _x_max, _y_max DOUBLE;
  DECLARE _x_new, _y_new DOUBLE;
  DECLARE _t_offset, _start, _end, _window_end INT;
  DECLARE _details TEXT;
  
  /* 0) Find all paths we may overlap with */
  DECLARE _done INT DEFAULT 0;
  DECLARE _cur CURSOR FOR
    SELECT s.radius, p.`id`, `stack_id`, `x1`, `y1`, `x2`, `y2`, `speed`, `i`, `j`, `i_unit`, `j_unit`, `time_start`, `time_end`, `time_check`, `x_check`, `y_check`, `x_min`, `y_min`, `x_max`, `y_max`
    FROM `paths` AS p
    INNER JOIN `stacks` AS s
       ON s.id = p.stack_id
    WHERE `time_start` <= NEW.`time_end`
      AND `time_end` > NEW.`time_start`
      AND `stack_id` != NEW.`stack_id`
      AND `x_min` <= NEW.`x_max`
      AND `x_max` <= NEW.`x_max`
      AND `y_min` <= NEW.`y_max`
      AND `y_max` <= NEW.`y_max`;
  
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET _done = 1;

  SELECT `radius`
  INTO _r_new
  FROM `stacks`
  WHERE `id` = NEW.`stack_id`;

  
  OPEN _cur;
  checking: LOOP
    /* 1) Pull out all rows we may overlap with and adjust their position for our start time */
    FETCH _cur INTO _r, _id, _stack_id, _x1, _y1, _x2, _y2, _speed, _i, _j, _i_unit, _j_unit, _time_start, _time_end, _time_check, _x_check, _y_check, _x_min, _y_min, _x_max, _y_max;
    
    IF _done THEN LEAVE checking; END IF;
    
    IF _time_start < NEW.time_start THEN
      SET _x_new = NEW.x1;
      SET _y_new = NEW.y1;
      SET _t_offset = NEW.time_start;
      CALL vTimePos( _x1, _y1, _i_unit, _j_unit, (NEW.time_start - _time_start), _x1, _y1 );
    ELSE
      SET _t_offset = _time_start;
      CALL vTimePos( NEW.x1, NEW.y1, NEW.i_unit, NEW.j_unit, (_time_start - NEW.time_start), _x_new, _y_new );
    END IF;
    
    /* 2) Do some calcluations */
    CALL vDistTime( _x_new, _y_new, _x1, _y1, NEW.i_unit, NEW.j_unit, _i_unit, _j_unit, (_r_new + _r), _start, _end );
    
    IF _start IS NULL THEN
      ITERATE checking;
    END IF;
    
    SET _window_end = IF( (_time_end < NEW.time_end), _time_end, NEW.time_end );
    SET _start = _start + _t_offset;
    IF _start > _window_end THEN
      ITERATE checking;
    END IF;
    
    SET _end = _end + _t_offset;
    IF _end > _window_end THEN
      SET _end = _window_end;
    END IF;
    
    /* 3) if we get to here, then we've got a path that we contact */
    INSERT INTO `events`
      ( `stack_id_1`, `path_id_1`, `stack_id_2`, `path_id_2`, `event`, `time_start`, `time_end` )
      VALUES
      ( NEW.stack_id, NEW.id, _stack_id, _id, "meet", _start, _end );
    
    ITERATE checking;
  END LOOP;
  
END;
//

DELIMITER ;


# Test code
DELETE FROM paths WHERE `id` = 1001;
TRUNCATE `events`;

INSERT INTO paths (`id`, `stack_id`, `x1`, `y1`, `x2`, `y2`, `speed`, `time_start`)
VALUES
(1001, 1, 0, 250, 500, 250, 5, 50 );

SELECT COUNT(*) FROM `events`;

# Or...
TRUNCATE `events`;
TRUNCATE `paths`;

INSERT INTO `paths` (`stack_id`, `x1`, `y1`, `x2`, `y2`, `speed`, `time_start`)
SELECT `stack_id`, `x1`, `y1`, `x2`, `y2`, `speed`, `time_start`
FROM `paths_bak`;

# And then ...
CALL vDistTime( @x_new, @y_new, 450, 416, -0.929960720173931, 0.367658889371089, 0.927258217012674, 2.85310220619284, 34, @start, @end ); SELECT @start, @end;
