init();

	function init() {
		initProperties();

		eventActivateCameraUpdate()
		    .eventBtnCaptureUpdate();
	}

	function initProperties() {
		$captureVideoUpdate = document.querySelector('#captureVideoUpdate');
		$canvasUpdate = document.querySelector('#canvasUpdate');
		canvasUpdateContext = $canvasUpdate.getContext('2d');

		$btnCaptureUpdate = $('#btnCaptureUpdate');
		$errorMessageUpdate = $('#errorMessageUpdate');
		dataURL = '';

		$activateCameraUpdate = $('#activateCameraUpdate');
		$checkedCamera = $activateCameraUpdate.find('#checkedCameraUpdate');

		iconCheck = '<i class="fas fa-check text-navy"></i>';
		iconTimes = '<i class="fas fa-circle text-danger"></i>';

		constraints = {
			audio: false,
			video: true,
		};

		errorName = {
			webcam: null,
		};
	}

	async function userMedia() {
		try {
			stream = await navigator.mediaDevices.getUserMedia(constraints);
			successStream();
		}

		catch(error) {
			if(error.name === 'NotAllowedError') {
				errorName.webcam = error.name;
				showErrors();
			} else {
				$errorMessageUpdate.html(error.toString());
			}

			$checkedCamera.html(iconTimes);
		}
	}

	function successStream() {
		$captureVideoUpdate.srcObject = stream;
		$checkedCamera.html(iconCheck);
	}

	function showErrors() {
		if(!stream){
			$errorMessageUpdate.html("Untuk mengambil gambar, pastikan anda mengizinkan kamera untuk aktif.");
			return true;
		}

		if(errorName.webcam === 'NotAllowedError') {
			$errorMessageUpdate.html("Untuk melakukan absen, pastikan anda mengizinkan kamera untuk aktif.");
			return true;
		}

		return false;
	}

	function eventActivateCameraUpdate() {
		$activateCameraUpdate.on('click', function(){
			userMedia();
		});

		return this;
	}

	function eventBtnCaptureUpdate() {
		$btnCaptureUpdate.on('click', function(){
			if(showErrors()) return;

			const captureVideoUpdateWidth = $captureVideoUpdate.offsetWidth;
			const captureVideoUpdateHeight = $captureVideoUpdate.offsetHeight;

			$canvasUpdate.width  = captureVideoUpdateWidth;
			$canvasUpdate.height = captureVideoUpdateHeight;

			canvasUpdateContext.drawImage($captureVideoUpdate, 0, 0, captureVideoUpdateWidth, captureVideoUpdateHeight);

			if($(this).hasClass('captureUpdate')) {
				$captureVideoUpdate.pause();
				dataURL = $canvasUpdate.toDataURL('image/jpeg', 0.9);

				$(this).html('<i class="fas fa-sync"></i> Ulang');
			}

			if($(this).hasClass('recaptureUpdate')) {
				$captureVideoUpdate.play();
				dataURL = '';

				$(this).html('<i class="fas fa-camera"></i> Ambil');
			}

			$(this).toggleClass('captureUdpate recaptureUpdate');
			$errorMessageUpdate.html('');
		});

		return this;
	}

	function resetStream() {
		dataURL = '';
		$captureVideoUpdate.pause();
		$captureVideoUpdate.src = "";

		const tracks = stream.getTracks();
		tracks[0].stop();

		stream.removeTrack(tracks[0]);
	}
