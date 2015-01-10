/*
 * Move.cpp
 *
 *  Created on: 15 May 2010
 *      Author: Dave
 */

#include "move.h"

namespace tdv {

Move::Move() {
	this->x1 = 0;
	this->y1 = 0;
	this->x2 = 0;
	this->y2 = 0;
	this->t1 = 0;
	this->s  = 0;
}

Move::Move( int x1, int y1, int x2, int y2, int t1, int s ) {
	this->x1 = x1;
	this->y1 = y1;
	this->x2 = x2;
	this->y2 = y2;
	this->t1 = t1;
	this->s  = s ;
}

Move::~Move() {
	// TODO Auto-generated destructor stub
}

}
