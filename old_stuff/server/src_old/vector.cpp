/*
 * Vector.cpp
 *
 *  Created on: 19 Feb 2010
 *      Author: Dave
 */

#include "Vector.h"

namespace vortexlib {

Vector2d::Vector2d() {
	this->x = 0;
	this->y = 0;
	this->mag = -1;
}

Vector2d::~Vector2d() {
}


void Vector2d::init( const int x, const int y ) {
	this->x = x;
	this->y = y;
	this->mag = -1;
}

void Vector2d::init( const int x, const int y, const float speed ) {
	float factor;
	factor = utils::hypotenuse(x, y) / speed;
//	std::cout << "init with " << x << ", " << y << ", " << speed << " gives " << (x / factor) << ", " << (y / factor) << std::endl;

	this->x = utils::round(x / factor);
	this->y = utils::round(y / factor);
	this->mag = -1;
}

float Vector2d::getMag() {
	if (this->mag < 0) {
		this->mag = utils::hypotenuse(this->x, this->y);
	}
	return this->mag;
}

void Vector2d::getUnitVector( Vector2d &v, const float speed ) {
	v.init( this->x, this->y, speed );
}

std::string Vector2d::toString() const {
	std::string rstr;
	std::stringstream out;
	out << "x: " << this->x << ", y: " << this->y << ", mag: " << this->mag;
	rstr = out.str();

	return rstr;
}

std::string Vector2d::toString() {
	std::string rstr;
	std::stringstream out;
	out << "x: " << this->x << ", y: " << this->y << ", mag: " << this->getMag();
	rstr = out.str();

	return rstr;
}

}
