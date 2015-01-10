/*
 * Path.h
 *
 *  Created on: 21 Feb 2010
 *      Author: Dave
 */

#ifndef PATH_H_
#define PATH_H_
#include <ctime>
#include <map>
#include <set>
#include <vector>
#include <iostream>
#include "Point.h"
#include "Vector.h"
#include "Meeting.h"

namespace vortexlib {

struct pathComp;

class Path {
	Point pStart, pEnd;
	Vector2d v; // unit vector for this path
	int id
		, tStart, tEnd, duration
		, radius
		, minX, minY, maxX, maxY;
	float speed, foo;

public:
	Path();
	virtual ~Path();

	void init( const int &id, const Point &pStart, const Point &pEnd, const int &r, const float &speed, const int &tStart = 0 );
	void init( const int &id, const int &x1, const int &y1, const int &x2, const int &y2, const int &r, const float &speed, const int &tStart = 0 );
	inline int getId()     const { return this->id; }
	inline int getRadius() const { return this->radius; }
	inline int getMinX()   const { return this->minX; }
	inline int getMinY()   const { return this->minY; }
	inline int getMaxX()   const { return this->maxX; }
	inline int getMaxY()   const { return this->maxY; }

	Point positionAt( const int &timestamp ) const;
	Point positionAfter( const int &tDif ) const;
	int timeAt( const Point &pos ) const;
	int timeTo( const Point &pos ) const;

	float distanceAtTime( const Path &pIn, const int &time) const;
	int cpaTime( const Path &pIn ) const;
	int timeAtDistance( const Path &pIn, const float &dist) const;
	void getMeetings( Meeting meetings[], int &mMax, Path paths[], int pCount ) const;
	void getMeetings( std::map< int, Meeting > &meetings, int &mMax, std::map< int, Path > &paths, const int pCount ) const;
	void getMeetings(
			  std::map< int, Meeting > &meetings
			, int &mMax
			, std::map< int, std::map< int, std::map< int, std::map< int, std::vector<Path> > > > > &paths
			, int pCount ) const;

	std::string toString() const;

private:
	void initDerived( const float &speed );
};

}

#endif /* PATH_H_ */
