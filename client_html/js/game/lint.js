function Lint(from, to, interpolation, accuracy) {
//linear interpolation
	
	if (accuracy == undefined) {
		this.margin = 0.001;
	} else {
		this.margin = accuracy;
	}

	lintVal = from + ((to - from) * interpolation);
	
	//set to target value if within margin
	if (Math.abs(lintVal - to) < margin) {
		lintVal = to;
	}

	return lintVal;
			
}