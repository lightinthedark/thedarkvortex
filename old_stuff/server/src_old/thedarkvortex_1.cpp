/*
 * main.cpp
 *
 *  Created on: 12 Dec 2009
 *      Author: Dave
 */

#include <iostream>
#include <iomanip>
#include <cstdlib>
#include <set>
#include <map>
#include <vector>
#include "utils.h"
#include "point.h"
#include "vector.h"
#include "path.h"
#include "meeting.h"

using namespace std;

void populateGrid(map< int, map< int, vector< int > > > &grid, set<int> &meets, int i, int p1x, int p1y, int p2x, int p2y, int xyRange, int gridSize)
{
	set<int>::iterator mEnd = meets.end(), mCur;
	vector<int>::iterator gIt, gItS, gItE;
	float m, c, yMin, yMax;
	int jMin, jMax, kMin, kMax, xMin, xMax, *kMinPtr, *kMaxPtr;
	bool weHere;

	jMin =  (((p1x < p2x) ? p1x : p2x) + xyRange) / gridSize;
	jMax = ((((p1x < p2x) ? p2x : p1x) + xyRange) / gridSize) + 1;

	if( p2y == p1y ) {
		// *** Need to do something proper here
		return;
	}
	// y = mx + c
	m = (float)(p2y - p1y) / (p2x - p1x);
	c = p1y - (m * p1x);
	if( m > 0 ) { // positive gradients
		kMinPtr = &kMin;
		kMaxPtr = &kMax;
	}
	else {
		kMinPtr = &kMax;
		kMaxPtr = &kMin;
	}

	// *** Debug info
//	cout << "line: " << p1x << "," << p1y << " " << p2x << "," << p2y << endl;
//	cout << "jMin: " << jMin << ", jMax: " << jMax << endl;
//	cout << "(p2x - p1x) = " << (p2x - p1x) << endl;
//	cout << "(p2y - p1y) = " << (p2y - p1y) << endl;
//	cout << setprecision(6);
//	cout << "m = " << m << ", c = " << c << endl;

	for( int j = jMin; j < jMax; j++) {
		// when x = min bound for this column, what's our y?
		xMin = -xyRange + j*gridSize;
		yMin = m * xMin + c;
		kMin = (int)((yMin + xyRange) / gridSize);

		// when x = max bound for this column, what's our y?
		xMax = -xyRange + (j+1)*gridSize;
		yMax = m * xMax + c;
		kMax = (int)((yMax + xyRange) / gridSize);

		// *** Debug info
//		cout << "j=" << j << endl;
//		cout << "xMin=" << xMin << ", yMin=" << yMin << ", kMin=" << kMin << "from " << ((yMin + xyRange) / gridSize) << endl;
//		cout << "xMax=" << xMax << ", yMax=" << yMax << ", kMax=" << kMax << "from " << ((yMax + xyRange) / gridSize) << endl;
//		cout << " kMin:" << kMin << " kMax:" << kMax << endl;

		// therefore all cells in this column corresponding to a y between those values are touched by us
		for( int k = (*kMinPtr); k <= (*kMaxPtr); k++) {
			weHere = false;
			gItS = grid[j][k].begin();
			gItE = grid[j][k].end();
			for( gIt = gItS; gIt < gItE; gIt++ ) {
				if( (*gIt) == i ) {
					weHere = true;
				}
				else if( meets.find(*gIt) == mEnd ) {
					meets.insert((*gIt));
				}
			}
			if( weHere == false ) {
				grid[j][k].push_back(i);
			}
		}
	}

}

int main()
{
//	srand ( time(NULL) );
	srand ( 1 );
	vortexlib::Timer t;
	t.start();

	// stress testing
	const int pMax = 1000000, pNew = 50000;
	const int xyRange = 1000000, dRange = 1000, rRange = 100, sRange = 10, gridRes = 1000;
//	const int pMax = 100, pNew = 1;
//	const int xyRange = 100,     dRange = 1000, rRange = 20,  sRange = 10, gridRes = 200;
	const float xyScale = (2 * xyRange)/(RAND_MAX + 1.0)
			, dScale  = (2 * dRange )/(RAND_MAX + 1.0);
	cout << "generating " << pMax << " stored background paths" << endl;
	cout << "then " << pNew << " new paths" << endl;
	cout << "dScale: " << dScale << endl;
	cout << "dRange: " << dRange << endl;
	map< int, vortexlib::Path > allPaths;
	map< int, set<int> > meets;
	// the n*n grid overlays the map which covers -m to +m (on x and y axis),
	// squareSize = (2m/n)
	// so grid entry n, 0 = -m+(n*squareSize),-m;
	// coordinate x, -m = (m+x)/squareSize, (m-m)/squareSize
	vortexlib::Path pTmp;
	map< int, map< int, vector< int > > > grid;
	const int gridSize = 2 * xyRange / gridRes;
	t.mark();
	int x1, y1, x2, y2, r, s;
	int p1x, p1y, p2x, p2y, p3x, p3y, p4x, p4y;
	float dx, dy, scale, u, v;

//*
	// create an initial population of pMax paths
	// start counting from 1 as id 0 is reserved for temporary paths
	for( int i = 1; i < pMax; i++ ) {
		x1 = (int)(xyScale * rand() - xyRange);
		y1 = (int)(xyScale * rand() - xyRange);
		x2 = (int)(x1 + dScale * rand() - dRange);
		y2 = (int)(y1 + dScale * rand() - dRange);
		r  = rand() % rRange;
		s  = rand() % sRange;

//		cout << "creating " << i << " with " << x1 << "," << y1 << " " << x2 << "," << y2 << " r:" << r << ", s:" << s << endl << flush;
		pTmp.init( i, x1, y1, x2, y2, r, s );
		allPaths[i] = pTmp;

		// now find all the grid squares that this path touches
		dx = x2 - x1;
		dy = y2 - y1;
		scale = sqrt( pow(dx, 2) + pow(dy, 2) ) / (float)r;
		u = dx / scale;
		v = dy / scale;

		p1x = (int)(x1 - u + v);  p1y = (int)(y1 - u - v);
		p2x = (int)(x1 - u - v);  p2y = (int)(y1 + u - v);
		p3x = (int)(x2 + u - v);  p3y = (int)(y2 + u + v);
		p4x = (int)(x2 + u + v);  p4y = (int)(y2 - u + v);

		// check bounding lines
		populateGrid(grid, meets[i], i, p1x, p1y, p2x, p2y, xyRange, gridSize);
		populateGrid(grid, meets[i], i, p2x, p2y, p3x, p3y, xyRange, gridSize);
		populateGrid(grid, meets[i], i, p3x, p3y, p4x, p4y, xyRange, gridSize);
		populateGrid(grid, meets[i], i, p4x, p4y, p1x, p1y, xyRange, gridSize);
	}
	t.mark();

	// create a busy second's worth (pNew) new paths
	// start counting from 1 as id 0 is reserved for temporary paths
	for( int i = 1; i < pNew; i++ ) {
		x1 = (int)(xyScale * rand() - xyRange);
		y1 = (int)(xyScale * rand() - xyRange);
		x2 = (int)(x1 + dScale * rand() - dRange);
		y2 = (int)(y1 + dScale * rand() - dRange);
		r  = rand() % rRange;
		s  = rand() % sRange;

//		cout << "creating " << i << " with " << x1 << "," << y1 << " " << x2 << "," << y2 << " r:" << r << ", s:" << s << endl << flush;
		pTmp.init( i, x1, y1, x2, y2, r, s );
		allPaths[i] = pTmp;

		// now find all the grid squares that this path touches
		dx = x2 - x1;
		dy = y2 - y1;
		scale = sqrt( pow(dx, 2) + pow(dy, 2) ) / (float)r;
		u = dx / scale;
		v = dy / scale;

		p1x = (int)(x1 - u + v);  p1y = (int)(y1 - u - v);
		p2x = (int)(x1 - u - v);  p2y = (int)(y1 + u - v);
		p3x = (int)(x2 + u - v);  p3y = (int)(y2 + u + v);
		p4x = (int)(x2 + u + v);  p4y = (int)(y2 - u + v);

		// check bounding lines
		populateGrid(grid, meets[i], i, p1x, p1y, p2x, p2y, xyRange, gridSize);
		populateGrid(grid, meets[i], i, p2x, p2y, p3x, p3y, xyRange, gridSize);
		populateGrid(grid, meets[i], i, p3x, p3y, p4x, p4y, xyRange, gridSize);
		populateGrid(grid, meets[i], i, p4x, p4y, p1x, p1y, xyRange, gridSize);
	}

	set<int>::iterator it, itStart, itEnd;
	set< int > meetTmp;

//*
	t.mark();
	vortexlib::Path *pTmp1, *pTmp2;
	int time, dist0, d0Count = 0, allCount = 0;
	for( int i = 0; i < pNew; i++ ) {
		pTmp1 = &(allPaths[i]);
		meetTmp = meets[i];
		itStart = meetTmp.begin();
		itEnd = meetTmp.end();
		for( it = itStart; it != itEnd; it++ ) {
			allCount++;
			pTmp2 = &(allPaths[(*it)]);
			time = pTmp1->cpaTime( (*pTmp2) );
			if( time < 0 ) {
				dist0 = pTmp1->distanceAtTime( *pTmp2, 0 );
				if( (dist0 < (2*rRange)) && (dist0 < (pTmp1->getRadius() + pTmp2->getRadius())) ) {
					d0Count++;
				}
			}
		}
	}
	cout << allCount << " total, " << d0Count << " close at start" << endl;
// */

	t.stop();

	// */
		// make some nice output
	/*
		vector< int >::iterator gridIt;
		for( int j=0; j<=gridRes; j++ ) {
			for( int k=0; k<=gridRes; k++ ) {
				cout << "(";
				for( gridIt = grid[j][k].begin(); gridIt < grid[j][k].end(); gridIt++ ) {
					cout << (*gridIt) << " ";
				}
				cout << "),";
			}
			cout << endl;
		}
	// */
		// Some actually useful output about how things went
	int mTmp = 0, mPeak = 0, mCount = 0;
	for( int i = 0; i < pMax; i++ ) {
		meetTmp = meets[i];
		mTmp = 0;
		for( it = meetTmp.begin(); it != meetTmp.end(); it++ ) {
			mTmp++;
			mCount++;
		}
		if( mTmp > mPeak ) { mPeak = mTmp; }
	}
	cout << "total meetings: " << mCount << endl << "peak: " << mPeak << endl;

	cout << t.print() << endl;
	cout << "done" << endl;
	return 0;
// */
}






// ############################################

int main_old()
{
	srand ( time(NULL) );
	vortexlib::Timer t;
	t.start();

	// Vector2d tests
/*
	vortexlib::Vector2d u, v, w;
	u.init( 3, -1 );
	cout << "u1: " << u.toString() << endl;
	cout << "v1: " << v.toString() << endl;
	v = u;
	cout << "v2: " << v.toString() << endl;
	v.init( 4, 9 );
	cout << "v3: " << v.toString() << endl;
//	cout << endl;

	w = u;
	cout << "w1: " << w.toString() << endl;
	w.add(3);
	cout << "w2: " << w.toString() << endl;
	w.add(v);
	cout << "w3: " << w.toString() << endl;
	w.sub(3);
	cout << "w4: " << w.toString() << endl;
	w.sub(v);
	cout << "w5: " << w.toString() << endl;
	w.mul(3);
	cout << "w6: " << w.toString() << endl;
	w.div(3);
	cout << "w7: " << w.toString() << endl;
	int num = w.dot(v);
	cout << "dot product w and v: " << num << endl;
	w.init( 12000, 4000);
	w.getUnitVector( u, 12650 );
	cout << "u7: " << u.toString() << endl;
	w.init( 12000, 4000);
	w.getUnitVector( u, 12 );
	cout << "u8: " << u.toString() << endl;
//	w.init( 1200000000, 400000000);
//	cout << "u9: " << w.toString() << endl;
	cout << endl;

	vortexlib::utils::sleep( 500 );
	t.mark();
// */

	// Point tests
/*
	vortexlib::Point pStart, pEnd;
	pStart.init( 1, 1 );
	pEnd.init( 40000, 50000 );
	vortexlib::Vector2d vTmp;
	cout << "start: " << pStart.toString() << endl;
	cout << "end: "   << pEnd.toString()   << endl;
	cout << "gap: "   << pStart.to(pEnd)    << endl;
	vTmp = pStart.vectorTo(pEnd);
	cout << "vTmp 1 : "   << vTmp.toString() << endl;
	vTmp = pEnd.vectorTo(pStart, 2);
	cout << "vTmp 2 : "   << vTmp.toString() << endl;
	cout << endl;

	vortexlib::utils::sleep( 500 );
	t.mark();
// */

	// Path tests
/*
	vortexlib::Path path1, path2;
	vortexlib::Point paStart, paEnd;

	path1.init( 1, paStart, paEnd, 100, 123);
	cout << "* path 1: " << endl << path1.toString() << endl;
	path2.init( 2, 40000, -3, 20, 50000, 100, 123);
	cout << "* path 2: " << endl << path2.toString() << endl;

	// time-based position:
	cout << "path 2 after 0  : " << path2.positionAfter( 0 ).toString() << endl;
	cout << "path 2 after 500: " << path2.positionAfter( 500 ).toString() << endl;
	cout << "path 2 after 519: " << path2.positionAfter( 519 ).toString() << endl;
	cout << "path 2 after 520: " << path2.positionAfter( 520 ).toString() << endl;
	cout << "path 2 after 521: " << path2.positionAfter( 521 ).toString() << endl;
	cout << "path 2 after 600: " << path2.positionAfter( 600 ).toString() << endl;

	// position-based time:

	// relative position / time:
	cout << "cpa for paths 1 and 2: " << path1.cpaTime( path2 ) << endl;
	cout << "cpa for paths 2 and 1: " << path2.cpaTime( path1 ) << endl;
	cout << "cpa for paths 1 and 1: " << path1.cpaTime( path1 ) << endl;

	cout << "path 1 is " << sizeof(path1) << endl;
	cout << "path 2 is " << sizeof(path2) << endl;
	cout << "point 1 is " << sizeof(paStart) << endl;
	cout << "point 2 is " << sizeof(paEnd) << endl;
	return 0;
// */

//*
/*
	// test the bounds functions
	// ...


	// stress testing
	t.mark();
//	const int pMax = 700000, pNew = 1000;
	const int pMax = 500000, pNew = 1000;
//	const int pMax = 30000, pNew = 1000;
//	const int pMax = 30, pNew = 1;
	cout << "generating " << pMax << " stored background paths" << endl;
	const int xyRange = 1000000, dRange = 1000, rRange = 100, sRange = 10;
//	const int xyRange = 10, dRange = 1000, rRange = 10, sRange = 10;

//	vortexlib::Path    *pathArr = new vortexlib::Path[pMax];
//	vortexlib::Meeting *meetArr = new vortexlib::Meeting[pMax * (pNew / 10)];
	vortexlib::Path    pTmp;
//	vortexlib::Meeting mTmp;

//	vector< vortexlib::Path > newPaths;
//	vector< vortexlib::Path >::iterator pIt;

//	map< int, vortexlib::Path > pathMapLame;
//	map< int, vortexlib::Meeting > meetArr2;

//	map< int, vortexlib::Meeting > meetings;
//	map< int, vortexlib::Meeting >::iterator mIt;

	//   minX      maxX      minY      maxY
	map< int, map< int, map< int, map< int, vector<vortexlib::Path> > > > > pathMap;

	int x1, y1, x2, y2, r, s, mCount;
// */
/*
	// create an initial population of pMax paths
	// start counting from 1 as id 0 is reserved for temporary paths
	for( int i = 1; i < pMax; i++ ) {
//		x1 = (std::rand() %xyRange) * 1;
//		y1 = (std::rand() %xyRange) * 1;
//		x2 = (std::rand() %xyRange) * 1;
//		y2 = (std::rand() %xyRange) * 1;
//		r  = (std::rand() %rRange)  * 1;
//		s  = (std::rand() %sRange);
		x1 = (std::rand() %xyRange) * 1;
		y1 = (std::rand() %xyRange) * 1;
		x2 = x1 + (std::rand() %(2*dRange)) * 1 - dRange;
		y2 = y1 + (std::rand() %(2*dRange)) * 1 - dRange;
		r  = (std::rand() %rRange)  * 1;
		s  = (std::rand() %sRange);

		pTmp.init( i, x1, y1, x2, y2, r, s );
//		pathArr[i] = pTmp;
//		pathMapLame[i] = pTmp;
		pathMap[pTmp.getMinX()][pTmp.getMaxX()][pTmp.getMinY()][pTmp.getMaxY()].push_back(pTmp);
	}
	vortexlib::utils::sleep(5000);
	cout << "done" << endl;
	return 0;
// */
/*
	cout << "======================" << endl;
	cout << "all paths (for ref)" << endl;
	cout << endl;
	for ( pIt=paths.begin(); pIt != paths.end(); pIt++ ) {
	    pTmp = (*pIt);
		cout << pTmp.toString() << endl;
	}
	cout << endl;
*/

/*
	cout << "generating " << pNew << " new paths" << endl;

	// create pNew more paths to test calculations for
	for( int i = 0; i < pNew; i++ ) {
		x1 = (std::rand() %xyRange) * 1;
		y1 = (std::rand() %xyRange) * 1;
		x2 = x1 + (std::rand() %(2*dRange)) * 1 - dRange;
		y2 = y1 + (std::rand() %(2*dRange)) * 1 - dRange;
		r  = (std::rand() %rRange)  * 1;
		s  = (std::rand() %sRange);
		pTmp.init( i, x1, y1, x2, y2, r, s );
		newPaths.push_back( pTmp );
	}
// */
/*
	cout << "======================" << endl;
	cout << "new paths (for ref)" << endl;
	cout << endl;
	for ( pIt=newPaths.begin(); pIt != newPaths.end(); pIt++ ) {
		pTmp = (*pIt);
		cout << pTmp.toString() << endl;
	}
	cout << endl;
*/
/*
	t.mark();

	// array-based - test calculations to find meetings with other paths
	mCount = 0;
	for( pIt=newPaths.begin(); pIt != newPaths.end(); pIt++ ) {
		pTmp = (*pIt);
		pTmp.getMeetings( meetArr, mCount, pathArr, pMax );
	}
	cout << "======================" << endl;
	cout << "found  " << mCount << " total +ve" << endl;
	cout << "met paths from array method" << endl;
	for( int i = 0; i < mCount; i++ ) {
//		cout << meetArr[i].toString() << endl;
	}
	// loop in here
	cout << endl;
	t.mark();
// */

	// simple-map-based - test calculations to find meetings with other paths
/*
	mCount = 0;
	for( pIt=newPaths.begin(); pIt != newPaths.end(); pIt++ ) {
		pTmp = (*pIt);
		pTmp.getMeetings( meetArr2, mCount, pathMapLame, pMax );
	}
	cout << "======================" << endl;
	cout << "found  " << mCount << " total +ve" << endl;
	cout << "met paths from simple-map method" << endl;
	for( int i = 0; i < mCount; i++ ) {
//		cout << meetArr[i].toString() << endl;
	}
	// loop in here
	cout << endl;
	t.mark();
// */

/*
	// Map-based - test calculations to find meetings with other paths
	mCount = 0;
	for( pIt=newPaths.begin(); pIt != newPaths.end(); pIt++ ) {
		pTmp = (*pIt);
		pTmp.getMeetings( meetings, mCount, pathMap, pMax );
	}
	cout << "======================" << endl;
	cout << "found  " << mCount << " total +ve" << endl;
	cout << "met paths from map method" << endl;
	for ( mIt=meetings.begin(); mIt != meetings.end(); mIt++ ) {
//	    mTmp = (*mIt).second;
//		cout << mTmp.toString() << endl;
	}
	cout << endl;
	t.mark();
// */
	t.stop();
	cout << t.print();

	return 0;
}
