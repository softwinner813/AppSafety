// (function() {
	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Init Signature DropDown
	 */

	var signDatas = localStorage.getItem('signDatas');
	signDatas = (signDatas == null) ? [] : JSON.parse(signDatas);


	addSignature(signDatas);
	function addSignature(signDatas) {
		for (var i = 0; i < signDatas.length; i++) {
		 	var data = signDatas[i];
		 	//console.log(data);
		 	var child = '<div class="dropdown-item signItem justify-content-between"><img src="' + data + '" height="40"><button type="button" class="btn btn-outline-danger btn-xs sign-del-btn" signdata="' + i +'"><i class="fas fa-trash"></i></button></div>';
	        $('#sign-dropdown').prepend(child);
		 }

		renderSignButtons(signDatas);
	}

	

	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Init Signature Pad
	 */

	var canvas = document.getElementById("drawCanvans");

	// window.onresize = resizeCanvas;
	// resizeCanvas();

	// // This also causes canvas to be cleared.
	function resizeCanvas() {
      // This part causes the canvas to be cleared
      var ratio =  Math.max(window.devicePixelRatio || 1, 1);
	  canvas.width = canvas.offsetWidth * ratio;
	  canvas.height = canvas.offsetHeight * ratio;
	  canvas.getContext("2d").scale(ratio, ratio);

	  // signaturePad.clear();
	}

	var signaturePad = new SignaturePad(canvas);




	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Init Signature width
	 */
	// document.getElementsByClassName('addSignBtn').addEventListener('click', function () {
	$('.addSignBtn').click(function(){
		setTimeout(function(){
            var width = $('#myTabContent1').innerWidth();
			//console.log(width);
			$('#drawCanvans').attr('width', width);
			resizeCanvas();
        }, 500);
	  	
	});





	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Clear Signature
	 */
	document.getElementById('clear').addEventListener('click', function () {
	  signaturePad.clear();
	});

	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Undo Signature
	 */
	document.getElementById('undo').addEventListener('click', function () {
		var data = signaturePad.toData();
	  if (data) {
	    data.pop(); // remove the last dot or line
	    signaturePad.fromData(data);
	  }
	});


	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Change Draw Color
	 */
	function changeColor(color) {
		signaturePad.clear();
		signaturePad.penColor = color;
	}

	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Save Signature
	 */
 	document.getElementById('saveSignBtn').addEventListener('click', function () {
		if (signaturePad.isEmpty()) {
		    return swal.fire({
                text:"Please provide a signature first.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                confirmButtonClass: "btn font-weight-bold btn-light"
            }).then(function(e) {
                KTUtil.scrollTop();
            });
	  	}
		
		// Crop and obtain the new canvas
		var trimmedCanvas = trimCanvas(canvas);
	  	var data = trimmedCanvas.toDataURL('image/png');

	  	signDatas.push(data);
	  	localStorage.setItem('signDatas', JSON.stringify(signDatas));

        $('#sign-dropdown').children('.signItem').remove();

        addSignature(signDatas);

        
		// renderSignButtons(signDatas);

	    signaturePad.clear();

		// Hide Modal
		$('#signModal').modal('hide');
	});

	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Generator Random String
	 */
	function makeid(length) {
	    var result           = '';
	    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	    var charactersLength = characters.length;
	    for ( var i = 0; i < length; i++ ) {
		      result += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
	   return result;
	}


	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Trim Canvas Image
	 */
	function trimCanvas(c) {
	    var ctx = c.getContext('2d'),
	        copy = document.createElement('canvas').getContext('2d'),
	        pixels = ctx.getImageData(0, 0, c.width, c.height),
	        l = pixels.data.length,
	        i,
	        bound = {
	            top: null,
	            left: null,
	            right: null,
	            bottom: null
	        },
	        x, y;
	    
	    // Iterate over every pixel to find the highest
	    // and where it ends on every axis ()
	    for (i = 0; i < l; i += 4) {
	        if (pixels.data[i + 3] !== 0) {
	            x = (i / 4) % c.width;
	            y = ~~((i / 4) / c.width);

	            if (bound.top === null) {
	                bound.top = y;
	            }

	            if (bound.left === null) {
	                bound.left = x;
	            } else if (x < bound.left) {
	                bound.left = x;
	            }

	            if (bound.right === null) {
	                bound.right = x;
	            } else if (bound.right < x) {
	                bound.right = x;
	            }

	            if (bound.bottom === null) {
	                bound.bottom = y;
	            } else if (bound.bottom < y) {
	                bound.bottom = y;
	            }
	        }
	    }
	    
	    // Calculate the height and width of the content
	    var trimHeight = bound.bottom - bound.top,
	        trimWidth = bound.right - bound.left,
	        trimmed = ctx.getImageData(bound.left, bound.top, trimWidth, trimHeight);

	    copy.canvas.width = trimWidth;
	    copy.canvas.height = trimHeight;
	    copy.putImageData(trimmed, 0, 0);

	    // Return trimmed canvas
	    return copy.canvas;
	}


	/*************************************
	 * @Auth : geniusdev0813 
	 * @Date : 2022.6.2
	 * @Desc : Render Signature Buttons
	 */

	function renderSignButtons(signDatas){
		if(signDatas.length > 0) {
			//console.log("Selected",signDatas[0]);
		 	document.getElementById('selected_sign').setAttribute('src', signDatas[0]);
		 	// $('#selected_sign').attr('src', signDatas[0]);
		 	$('#addSignBtnTitle').hide();
		 	$('#selected_sign').parent().show();
		 }  else {
		 	$('#selected_sign').parent().hide();
		 	$('#addSignBtnTitle').show();
		 }

		// Choose Sign Item
		var signItems=document.getElementsByClassName('signItem');  
		for (i = 0; i < signItems.length; i++) {
		    signItems[i].addEventListener("click", function(e){
		    	e.preventDefault();
		    	var sign = e.currentTarget.querySelector('img').getAttribute('src');
				//console.log("fdsfds", sign);
				document.getElementById('selected_sign').setAttribute('src', sign);
		    });
		}

		// Remove Sign Item
		var signDelBtns = document.getElementsByClassName('sign-del-btn');
		for (var i = 0; i < signDelBtns.length; i++) {
			signDelBtns[i].addEventListener("click", function(e){
				e.stopPropagation();
		    	var sign = e.currentTarget.getAttribute('signdata');
				signDatas.splice(sign * 1, 1);
				localStorage.setItem('signDatas', JSON.stringify(signDatas));

				$('#sign-dropdown').children('.signItem').remove();
				addSignature(signDatas);
		    });
		}
	}
// })();