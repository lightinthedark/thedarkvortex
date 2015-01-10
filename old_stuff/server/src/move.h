/*
 * move.h
 *
 *  Created on: 15 May 2010
 *      Author: Dave
 */

#ifndef MOVE_H_
#define MOVE_H_

namespace tdv {

class Move
{
private:
	int x1, y1, x2, y2, t1, t2, s;

public:
	Move();
	Move( int x1, int y1, int x2, int y2, int t1, int s );
	virtual ~Move();
};

}

#endif /* MOVE_H_ */
