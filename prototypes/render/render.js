/* Render canvas */

Function render() {
	//For each entry callback in list, in order:
		//If the callback has not been cancelled:
			//If the callback was specified with an element and the element may be visible:
				//Call the requestAnimationFrame operation of callback with time as the argument.
				//If calling the operation resulted in an exception being thrown, then catch that exception and ignore it.
				//Return to the beginning of the invoke callbacks algorithm
	//If no callbacks were fired in this iteration of the invocation algorithm, terminate these steps.

/* 
Note: The purpose of this algorithm is to ensure that the callback for all potentially visible elements is invoked for a frame even if a side effect of invoking one callback changes the visibility of an element associated with another callback. 
*/

//An element may be visible if the user agent determines that some portion of the element may be displayed.

/*
Note: Determining visibility precisely in all cases for all possible rendering models is a difficult task. If in doubt, it is always safe to assume that an element may be visible, while it is not safe to declare that an element is not visible if it might be.
*/

}

function start() {
	animationStartTime = Date.now();
	window.requestAnimationFrame(render, /*canvas object*/);
}