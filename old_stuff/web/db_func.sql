/* ~~~~~~~~~~~~
   Vector utils
   ~~~~~~~~~~~~ */
DELIMITER //


DROP PROCEDURE IF EXISTS vMag//

/**
 * Vector utility: Calculate the magnitude of a vector
 */
CREATE PROCEDURE vMag(
  IN _dx DOUBLE,
  IN _dy DOUBLE,
  OUT _magnitude DOUBLE )
DETERMINISTIC
BEGIN
  SET _magnitude = SQRT( _dx * _dx + _dy * _dy );
END;
//


DROP PROCEDURE IF EXISTS vUnit//

/**
 * Vector utility: Calculate the unit vector of a vector
 */
CREATE PROCEDURE vUnit(
  IN _dx DOUBLE,
  IN _dy DOUBLE,
  IN _speed INT,
  IN _magnitude DOUBLE,
  OUT _i DOUBLE,
  OUT _j DOUBLE )
DETERMINISTIC
BEGIN
  IF _magnitude IS NULL THEN
  	CALL vMag( _dx, _dy, _magnitude );
  END IF;
  
  IF _magnitude = 0 THEN
    SET _i = 0;
    SET _j = 0;
  ELSE
    SET _i = (_speed * _dx) / _magnitude;
    SET _j = (_speed * _dy) / _magnitude;
  END IF;
END;
//


DROP PROCEDURE IF EXISTS vAdd//

/**
 * Vector utility: Add two vectors
 */
CREATE PROCEDURE vAdd(
  IN _i1 DOUBLE,
  IN _j1 DOUBLE,
  IN _i2 DOUBLE,
  IN _j2 DOUBLE,
  OUT _i DOUBLE,
  OUT _j DOUBLE )
DETERMINISTIC
BEGIN
  SET _i = _i1 + _i2;
  SET _j = _j1 + _j2;
END;
//


DROP PROCEDURE IF EXISTS vSub//

/**
 * Vector utility: Subtract one vector from another
 */
CREATE PROCEDURE vSub(
  IN _i1 DOUBLE,
  IN _j1 DOUBLE,
  IN _i2 DOUBLE,
  IN _j2 DOUBLE,
  OUT _i DOUBLE,
  OUT _j DOUBLE )
DETERMINISTIC
BEGIN
  SET _i = _i1 - _i2;
  SET _j = _j1 - _j2;
END;
//


DROP PROCEDURE IF EXISTS vMul//

/**
 * Vector utility: Multiply a vector by a scalar value
 */
CREATE PROCEDURE vMul(
  IN _i1 DOUBLE,
  IN _j1 DOUBLE,
  IN _factor DOUBLE,
  OUT _i DOUBLE,
  OUT _j DOUBLE )
DETERMINISTIC
BEGIN
  SET _i = _i1 * _factor;
  SET _j = _j1 * _factor;
END;
//


DROP PROCEDURE IF EXISTS vDiv//

/**
 * Vector utility: Divide a vector by a scalar value
 */
CREATE PROCEDURE vDiv(
  IN _i1 DOUBLE,
  IN _j1 DOUBLE,
  IN _factor DOUBLE,
  OUT _i DOUBLE,
  OUT _j DOUBLE )
DETERMINISTIC
BEGIN
  SET _i = _i1 / _factor;
  SET _j = _j1 / _factor;
END;
//


DROP PROCEDURE IF EXISTS vDot//

/**
 * Vector utility: Multiply a vector by another, giving a scalar value
 */
CREATE PROCEDURE vDot(
  IN _i1 DOUBLE,
  IN _j1 DOUBLE,
  IN _i2 DOUBLE,
  IN _j2 DOUBLE,
  OUT _val DOUBLE )
DETERMINISTIC
BEGIN
  SET _val = (_i1 * _i2) + (_j1 * _j2);
END;
//


DROP PROCEDURE IF EXISTS vTimePos//

/**
 * Vector utility: Find the position at a particular time of a point moving along a vector
 */
CREATE PROCEDURE vTimePos(
  IN _x DOUBLE,
  IN _y DOUBLE,
  IN _i DOUBLE,
  IN _j DOUBLE,
  IN _t DOUBLE,
  OUT _xt DOUBLE,
  OUT _yt DOUBLE )
DETERMINISTIC
BEGIN
  DECLARE _it, _jt DOUBLE;
  CALL vMul( _i, _j, _t, _it, _jt );
  CALL vAdd( _x, _y, _it, _jt, _xt, _yt );
END;
//


DROP PROCEDURE IF EXISTS vTimeDist//

/**
 * Vector utility: Find the distance at a particular time between two points moving along 2 vectors
 */
CREATE PROCEDURE vTimeDist(
  IN _x1 DOUBLE,
  IN _y1 DOUBLE,
  IN _x2 DOUBLE,
  IN _y2 DOUBLE,
  IN _i1 DOUBLE,
  IN _j1 DOUBLE,
  IN _i2 DOUBLE,
  IN _j2 DOUBLE,
  IN _t DOUBLE,
  OUT _d DOUBLE )
DETERMINISTIC
BEGIN
  DECLARE _x1t, _y1t, _x2t, _y2t, _dx, _dy DOUBLE;
  CALL vTimePos( _x1, _y1, _i1, _j1, _t, _x1t, _y1t );
  CALL vTimePos( _x2, _y2, _i2, _j2, _t, _x2t, _y2t );
  CALL vSub( _x1t, _y1t, _x2t, _y2t, _dx, _dy );
  CALL vMag( _dx, _dy, _d );
END;
//


DROP PROCEDURE IF EXISTS vCpaTime//

/**
 * Vector utility: Find the time of closest point of approach of two points moving along 2 vectors
 */
CREATE PROCEDURE vCpaTime(
  IN _x1 DOUBLE,
  IN _y1 DOUBLE,
  IN _x2 DOUBLE,
  IN _y2 DOUBLE,
  IN _i1 DOUBLE,
  IN _j1 DOUBLE,
  IN _i2 DOUBLE,
  IN _j2 DOUBLE,
  OUT _t DOUBLE )
DETERMINISTIC
BEGIN
  DECLARE _uv_i, _uv_j, _uv_mag, _w_x, _w_y, _top, _bottom DOUBLE;
  CALL vSub( _i1, _j1, _i2, _j2, _uv_i, _uv_j );
  CALL vMag( _uv_i, _uv_j, _uv_mag );
  
  CALL vSub( _x1, _y1, _x2, _y2, _w_x, _w_y );
  CALL vMul( _w_x, _w_y, -1, _w_x, _w_y );
  CALL vDot( _w_x, _w_y, _uv_i, _uv_j, _top );
  
  SET _bottom = _uv_mag * _uv_mag;
  SET _t = _top / _bottom;
END;
//


DROP PROCEDURE IF EXISTS vDistTime//

/**
 * Vector utility: Find the times at which two points moving along 2 vectors are a particular distance apart
 */
CREATE PROCEDURE vDistTime(
  IN _x1 DOUBLE,
  IN _y1 DOUBLE,
  IN _x2 DOUBLE,
  IN _y2 DOUBLE,
  IN _i1 DOUBLE,
  IN _j1 DOUBLE,
  IN _i2 DOUBLE,
  IN _j2 DOUBLE,
  IN _d DOUBLE,
  OUT _t1 DOUBLE,
  OUT _t2 DOUBLE )
DETERMINISTIC
BEGIN
  /*
   * http://softsurfer.com/Archive/algorithm_0106/algorithm_0106.htm
   * apparently, D(t) = d(t)^2 = w(t)•w(t)
   * That becomes quadratic equation:
   * (u-v)•(u-v)t^2 + 2w(0)•(u-v)t + w(0)•w(0)
   * 
   * Also remember that quadratic equations can be solved with:
   * 0 = ax^2 + bx + c
   * x = ( -b +- sqrt( b^2 - 4ac) ) / 2a
   */
  
  DECLARE _a, _b, _c, _m, _wx, _wy, _wi, _wj DOUBLE;
  
  CALL vSub( _i1, _j1, _i2, _j2, _wi, _wj );
  CALL vSub( _x1, _y1, _x2, _y2, _wx, _wy );
  
  CALL vDot( _wx, _wy, _wx, _wy, _c );
  SET _c = _c - (_d * _d);
  CALL vMul( _wx, _wy, 2, _wx, _wy );
  CALL vDot( _wi, _wj, _wx, _wy, _b );
  CALL vDot( _wi, _wj, _wi, _wj, _a );
  
  SET _m = (_b * _b) - (4 * _a * _c );
#  SELECT _a, _b, _c, _m;
  
  IF _m < 0 THEN
    # if m is negative, there is no solution for the equation. ie the points do not ever get within range
    SET _t1 = NULL;
    SET _t2 = NULL;
  ELSE
    SET _m = SQRT( _m );
    
    SET _t2 = ( (_b * -1) + _m ) / ( 2 * _a );
    IF _t2 < 0 THEN
      # if the time period has already finished, there is no meeting in the future
      SET _t1 = NULL;
      SET _t2 = NULL;
    ELSE
      # if the time period has already started, then count it from now
      SET _t1 = ( (_b * -1) - _m ) / ( 2 * _a );
      IF _t1 < 0 THEN
        SET _t1 = 0;
      END IF;
    END IF;
#   SELECT _t1, _t2;
    
  END IF;
END;
//



/* ~~~~~~~~~~~~
   Unit utils
   ~~~~~~~~~~~~ */

DROP PROCEDURE IF EXISTS unitTimePosition//

/**
 * Unit utility: Find the position of a unit at a set time
 */
CREATE PROCEDURE unitTimePosition(
  IN _stack INT ,
  IN _time INT ,
  OUT _x DOUBLE ,
  OUT _y DOUBLE ,
  OUT _r DOUBLE )
BEGIN
  SELECT
    ( `x1` + (`i_unit` * (_time - `time_start`) ) ) ,
    ( `y1` + (`j_unit` * (_time - `time_start`) ) ) ,
    s.`radius`
  INTO _x, _y, _r
  FROM `stacks` AS s
  INNER JOIN `paths` AS p
     ON p.stack_id = s.id
  WHERE `time_start` <= _time
    AND `time_end` > _time
    AND s.`id` = _stack;

END;
//

DELIMITER ;
