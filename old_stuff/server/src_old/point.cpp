/*
 * Point.cpp
 *
 *  Created on: 20 Feb 2010
 *      Author: Dave
 */

#include "Point.h"

namespace vortexlib {

Point::Point() {
	this->x = 0;
	this->y = 0;
}

Point::~Point() {
}


void Point::init( const int x, const int y ) {
	this->x = x;
	this->y = y;
}

void Point::getTranslated( Point &pIn, const Vector2d &vIn ) const {
	pIn.x = this->x + vIn.getX();
	pIn.y = this->y + vIn.getY();
}

float Point::to( const Point &p) const {
	return sqrt( pow(this->x - p.x, 2) + pow(this->y - p.y, 2) );
}

Vector2d Point::vectorTo( const Point &p, const float speed ) const {
	Vector2d r;
	if( speed == 0 ) {
		r.init( p.x - this->x, p.y - this->y );
	}
	else {
		r.init( p.x - this->x, p.y - this->y, speed );
	}
	return r;
}

std::string Point::toString() const{
	std::stringstream out;
	out << "x: " << this->x << ", y: " << this->y;

	return out.str();
}

}
