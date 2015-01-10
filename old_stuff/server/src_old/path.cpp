/*
 * Path.cpp
 *
 *  Created on: 21 Feb 2010
 *      Author: Dave
 */

#include "Path.h"
#define SMALL_NUM 0.000001

namespace vortexlib {

Path::Path() {
	this->tStart = this->tEnd = (int)time(NULL);
	this->speed = 0;
	this->radius = 0;
}

Path::~Path() {
}

void Path::init( const int &id, const Point &pStart, const Point &pEnd, const int &r, const float &speed, const int &tStart ) {
	this->id = id;
	this->pStart = pStart;
	this->pEnd = pEnd;
	this->tStart = ( tStart == 0 ) ? (int)time(NULL): tStart;
	this->radius = r;
	this->initDerived( speed );
}

void Path::init( const int &id, const int &x1, const int &y1, const int &x2, const int &y2, const int &r, const float &speed, const int &tStart ) {
	this->id = id;
	this->pStart.init(x1, y1);
	this->pEnd.init(x2, y2);
	this->tStart = ( tStart == 0 ) ? (int)time(NULL): tStart;
	this->radius = r;
	this->initDerived( speed );
}

Point Path::positionAt( const int &timestamp ) const {
	int tDif = timestamp - this->tStart;
	return this->positionAfter( tDif );
}

Point Path::positionAfter( const int &tDif ) const {
	if( tDif >= this->duration ) {
		return this->pEnd;
	}
	else {
		Point r;
		Vector2d v = this->v;
		v.mul(tDif);
		this->pStart.getTranslated( r, v );
		return r;
	}
}

int Path::timeAt( const Point &pos ) const {
	return 0;
}

int Path::timeTo( const Point &pos ) const {
	return 0;
}

float Path::distanceAtTime( const Path &pIn, const int &time) const {
	Point p1, p2;
	p1 = this->positionAt( time );
	p2 = pIn.positionAt( time );
	return p1.to(p2);
}

int Path::cpaTime( const Path &pIn ) const {
	// http://softsurfer.com/Archive/algorithm_0106/algorithm_0106.htm

	Vector2d dv = this->v;
	dv.sub( pIn.v );
	float dv2 = dv.dot(dv);
	// early return if the paths are almost parallel?
	if( dv2 <= SMALL_NUM ) {
		return 0;
	}
	else {
		Vector2d w0 = pIn.pStart.vectorTo(this->pStart);
		return utils::round( -w0.dot(dv) / dv2 );
	}
}

int Path::timeAtDistance( const Path &pIn, const float &dist) const {
	// http://softsurfer.com/Archive/algorithm_0106/algorithm_0106.htm
	// apparently, D(t) = d(t)^2 = w(t)•w(t)
	// That becomes quadratic equation:
	// (u-v)•(u-v)t^2 + 2w(0)•(u-v)t + w(0)•w(0)
	//
	// Also remember that quadratic equations can be solved with:
	// 0 = ax^2 + bx + c
	// x = ( -b +- sqrt( b^2 - 4ac) ) / 2a

/*	// here's my old php to do the job. just an aide memoir
	$u = $p1->getUnit();
	$v = $p2->getUnit();
	$u_v = $u->sub($v);

	$w_0 = $p1->getFrom()->sub($p2->getFrom());

	$a = $u_v->dot($u_v);
	$b = $w_0->mult( 2 );
	$c = $w_0->dot($w_0) - ($dist * $dist);

	$t = ( (($b * -1) + sqrt( (($b*$b) - (4*$a*$c)) ) ) / (2*$a) );
	var_dump_pre( $t, 't: ')
// */

/*	// ... or the same again as a mysql function
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
// */
	return 0;
}

std::string Path::toString() const {
	std::stringstream out;
	out << "Path " << this->id << ": x range (" << this->minX << ", " << this->maxX << "), y range (" << this->minY << ", " << this->maxY << ")";
//	out << "Start: " << this->pStart.toString() << std::endl;
//	out << "End  : " << this->pEnd.toString() << std::endl;
//	out << "unitV: " << this->v.toString() << std::endl;
//	out << "for " << this->duration << "( well, really " << (this->pStart.to(this->pEnd) / this->speed) << ")" << " at " << this->speed << std::endl;
//	out << "Times: " << this->tStart << " to " << this->tEnd << std::endl;

	return out.str();
}

// array-based
void Path::getMeetings( Meeting meetings[], int &mMax, Path paths[], int pCount ) const {
	Meeting mTmp;
	int cpaTmp;
	for( int i = 0; i < pCount; i++ ) {
		if( (this->maxX >= paths[i].minX)
		 && (this->maxY >= paths[i].minY)
		 && (this->minX <= paths[i].maxX)
		 && (this->minY <= paths[i].maxY) ) {
//			cpaTmp = this->cpaTime( paths[i] );
			cpaTmp = 0;
			mMax++;
			if( cpaTmp > 0 ) {
//				meetings[mMax].init(cpaTmp, cpaTmp, (*this), paths[i]);
//				meetings[mMax].init(cpaTmp, cpaTmp, this->id, paths[i].id);
				mTmp.init(cpaTmp, cpaTmp, this->id, paths[i].id);
//				mMax++;
			}
		}
	}
}

// simple map based
void Path::getMeetings( std::map< int, Meeting > &meetings, int &mMax, std::map< int, Path > &paths, const int pCount ) const {
	std::map< int, Path >::iterator pIt;
	Meeting mTmp;
	int cpaTmp;
	for( pIt = paths.begin(); pIt != paths.end(); pIt++ ) {
		if( (this->maxX >= (*pIt).second.minX)
		 && (this->maxY >= (*pIt).second.minY)
		 && (this->minX <= (*pIt).second.maxX)
		 && (this->minY <= (*pIt).second.maxY) ) {
//			cpaTmp = this->cpaTime( (*pIt).second) );
			cpaTmp = 0;
			mMax++;
			if( cpaTmp > 0 ) {
//				meetings[mMax].init(cpaTmp, cpaTmp, (*this), paths[i]);
//				meetings[mMax].init(cpaTmp, cpaTmp, this->id, paths[i].id);
				mTmp.init(cpaTmp, cpaTmp, this->id, (*pIt).second.id);
//				mMax++;
			}
		}
	}
}


// map-based
void Path::getMeetings(
		  std::map< int, Meeting > &meetings
		, int &mMax
		, std::map< int, std::map< int, std::map< int, std::map< int, std::vector<Path> > > > > &mMinX
		, int pCount ) const {
	std::map< int, std::map< int, std::map< int, std::map< int, std::vector<Path> > > > >::iterator iMinX, iMinXLo, iMinXHi;
	std::map< int, std::map< int, std::map< int, std::vector<Path> > > >::iterator iMaxX, iMaxXLo, iMaxXHi;
	std::map< int, std::map< int, std::vector<Path> > >::iterator iMinY, iMinYLo, iMinYHi;
	std::map< int, std::vector<Path> >::iterator iMaxY, iMaxYLo, iMaxYHi;
	std::vector<Path>::iterator iPaths, iPathsLo, iPathsHi;
	Path p;
	Meeting mTmp;
	int cpaTmp;

	iMinXLo = mMinX.begin();
	iMinXHi = mMinX.upper_bound(this->maxX);
	for( iMinX = iMinXLo; iMinX != iMinXHi; iMinX++ ) { // go through all entries with minX < this->maxX
		// (*iMinX).second = next layer of map, which is indexed on maxX
		iMaxXLo = (*iMinX).second.lower_bound(this->minX);
		iMaxXHi = (*iMinX).second.end();
		for( iMaxX = iMaxXLo; iMaxX != iMaxXHi; iMaxX++ ) {
			// (*iMaxX).second = next layer of map, which is indexed on minY
			iMinYLo = (*iMaxX).second.begin();
			iMinYHi = (*iMaxX).second.upper_bound(this->maxY);
			for( iMinY = iMinYLo; iMinY != iMinYHi; iMinY++ ) { // go through all entries with minX < this->maxX
				// (*iMinY).second = next layer of map, which is indexed on maxY
				iMaxYLo = (*iMinY).second.lower_bound(this->minY);
				iMaxYHi = (*iMinY).second.end();
				for( iMaxY = iMaxYLo; iMaxY != iMaxYHi; iMaxY++ ) {
					// (*iMaxY).second = vector of paths
					iPathsLo = (*iMaxY).second.begin();
					iPathsHi = (*iMaxY).second.end();
					for( iPaths = iPathsLo; iPaths != iPathsHi; iPaths++ ) {
						mMax++;
//						cpaTmp = this->cpaTime( (*iPaths) );
						cpaTmp = 0;
						if( cpaTmp > 0 ) {
//							meetings[mMax].init( cpaTmp, cpaTmp, (*this), (*pIt) );
//							meetings[mMax].init( cpaTmp, cpaTmp, this->id, pIt->id );
							mTmp.init( cpaTmp, cpaTmp, this->id, iPaths->id );
//							mMax++;
						}
					}
				}
			}
	    }
	}
}


// Private (utility) functions
void Path::initDerived( const float &speed ) {
	this->v = this->pStart.vectorTo(this->pEnd, speed);
	this->speed = this->v.getMag();
	this->duration = (int)ceil(this->pStart.to(this->pEnd) / this->speed);
	this->tEnd = this->tStart + this->duration;
	this->minX = std::min( this->pStart.getX(), this->pEnd.getX() ) - this->radius;
	this->minY = std::min( this->pStart.getY(), this->pEnd.getY() ) - this->radius;
	this->maxX = std::max( this->pStart.getX(), this->pEnd.getX() ) + this->radius;
	this->maxY = std::max( this->pStart.getY(), this->pEnd.getY() ) + this->radius;
}

}
