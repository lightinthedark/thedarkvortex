/*
 * Point.h
 *
 *  Created on: 20 Feb 2010
 *      Author: Dave
 */

#ifndef POINT_H_
#define POINT_H_
#include <iostream>
#include <sstream>
#include <cmath>
#include "Vector.h"

namespace vortexlib {

class Point {
	int x, y;
public:
	Point();
	virtual ~Point();

	void init( const int x, const int y );
	inline int getX() const;
	inline int getY() const;
	void getTranslated( Point &pIn, const Vector2d &vIn ) const;
	float to( const Point &p ) const;
	Vector2d vectorTo( const Point &p, const float speed = 0) const;
	std::string toString() const;

	inline void add( const int val );
	inline void add( const Point &pIn );
	inline void sub( const int val );
	inline void sub( const Point &pIn );
};


inline int Point::getX() const {
	return this->x;
}
inline int Point::getY() const {
	return this->y;
}

inline void Point::add( const int val ) {
	this->x += val;
	this->y += val;
}

inline void Point::add( const Point &pIn ) {
	this->x += pIn.x;
	this->y += pIn.y;
}

inline void Point::sub( const int val ) {
	this->x -= val;
	this->y -= val;
}

inline void Point::sub( const Point &pIn ) {
	this->x -= pIn.x;
	this->y -= pIn.y;
}

}

#endif /* POINT_H_ */
