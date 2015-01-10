/*
 * Vector.h
 *
 *  Created on: 19 Feb 2010
 *      Author: Dave
 * To be capable of representing unit vectors such as:
 *               3 mm/s (Bagger 288)
 *              44 mm/s (shuttle crawler vehicle)
 *             134 mm/s (average walking speed)
 *          13 411 mm/s (30mph)
 *          26 822 mm/s (60mph)
 *          44 704 mm/s (100mph)
 *         340 290 mm/s (speed of sound at sea level
 *
 *     299 792 458 m/s (speed of light)
 * and other vectors such as
 *      40 041 000 m   (circumference of Earth)
 *     449 197 000 m   (circumference of Jupiter)
 *   2 147 483 647 (max int range)
 */

#ifndef VECTOR_H_
#define VECTOR_H_
#include <iostream>
#include <iomanip>
#include <sstream>
#include <cmath>
#include "Utils.h"

namespace vortexlib {

class Vector2d {
	int x, y;
	float mag;
public:
	Vector2d();
	virtual ~Vector2d();

	void init( const int x, const int y );
	void init( const int x, const int y, const float speed );
	inline int getX() const;
	inline int getY() const;
	float getMag();
	void getUnitVector( Vector2d &v, const float speed );
	std::string toString() const;
	std::string toString();

	inline Vector2d operator + ( const int val ) const;
	inline Vector2d operator + ( const Vector2d &vIn ) const;
	inline void add( const int val );
	inline void add( const Vector2d &vIn );
	inline void sub( const int val );
	inline void sub( const Vector2d &vIn );
	inline void mul( const int val );
	inline void div( const int val );
	inline int dot( const Vector2d &vIn ) const;
};


inline int Vector2d::getX() const {
	return this->x;
}
inline int Vector2d::getY() const {
	return this->y;
}

inline Vector2d Vector2d::operator + ( const int val ) const {
	Vector2d r;
	r.init( this->x + val, this->y + val );
	return r;
}

inline Vector2d Vector2d::operator + ( const Vector2d &vIn ) const {
	Vector2d r;
	r.init( this->x + vIn.x, this->y + vIn.y);
	return r;
}

inline void Vector2d::add( const int val ) {
	this->x += val;
	this->y += val;
	this->mag = -1;
}

inline void Vector2d::add( const Vector2d &vIn ) {
	this->x += vIn.x;
	this->y += vIn.y;
	this->mag = -1;
}

inline void Vector2d::sub( const int val ) {
	this->x -= val;
	this->y -= val;
	this->mag = -1;
}

inline void Vector2d::sub( const Vector2d &vIn ) {
	this->x -= vIn.x;
	this->y -= vIn.y;
	this->mag = -1;
}

inline void Vector2d::mul( const int val ) {
	this->x *= val;
	this->y *= val;
	this->mag = -1;
}

inline void Vector2d::div( const int val ) {
	this->x /= val;
	this->y /= val;
	this->mag = -1;
}

inline int Vector2d::dot( const Vector2d &vIn ) const {
	return this->x * vIn.x + this->y * vIn.y;
}

}


#endif /* VECTOR_H_ */
