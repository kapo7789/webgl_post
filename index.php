<!DOCTYPE html>
<html lang="en">
	<head>
		<title>NOTHING HERE YET</title>
		<meta charset="utf-8" />
		<link href='http://fonts.googleapis.com/css?family=Titillium+Web:700' rel='stylesheet' type='text/css'>
    
		<style>
			body {
				margin: 0px;
				background-color: #000000;
				overflow: hidden;
			}

			.wrap {
				position: absolute;
				width: 600px;
				height: 50px;
				background: #000;
				color: #fff;
				left:50%;
				top:10%;
				margin-left:-300px;
				margin-top:-50px;
				text-align: center;
				opacity:0.9;
			}

			.content {
				font: bold 18px 'Titillium Web' Arial;
				display: block;
				height: 100%;
				width: 100%;
				padding: 12px;
				text-shadow: 0px 0px 2px #fff;
			}
			.twitter-share-button {
				position: absolute;
				right: 0px;
				bottom: 10px;
				z-index: 999;
			}
			.umenu {
			    display: inline-block;
			    padding:10px;
			    position: absolute;
			    bottom:10px;
			    left:5px;
			    z-index:999;
			    line-style-type: none;
			}
			.umenu li {
			    display: inline;
			}
			.umenu a {
			    color: #000000;
			    background: #fff;
			    font: normal 12px 'Titillium Web' Arial;
			    padding: 3px;
	            margin:5px;
	            text-decoration: none;
	            border: solid 2px #000000;
			}
			.umenu a:hover {
			    background: #aaaaaa;
			}
			#slider {
			    width:400px;
			    position: absolute;
			    left:50%;
			    bottom:35px;
			    margin-left:-200px;
			}
			a.pressed {
			    border: solid 2px #ff2000;
			}
			.content a {
			    text-decoration:none;
			    color:#f00;
			    text-shadow: 0px 0px 2px #f00;
			}
			.content a:hover {
			    color: #f55;
			}
		</style>
        <link rel="stylesheet" href="css/jquery-ui.min.css">
	</head>
	<body>
        <input id="textto3d" type="text" style="display:none" value="<?php if(isset($_GET['text'])) echo $_GET['text']; ?>"/>
		<div class="wrap">
			<div class="content">
				c o d e : <a target="_blank" href="http://twitter.com/antistar999">k a p o</a> | 
				m u s i c: <a target="_blank" href="http://twitter.com/DaveKregg">u b e r</a> | 
				a r t i s t: <a target="_blank" href="http://www.youtube.com/user/MrAcread">c r a f t e r z</a>
			</div>
		</div>

		<script src="js/three.min.js"></script>
        <script src="js/shaders/FXAAShader.js"></script>
		<script src="js/shaders/CopyShader.js"></script>
		<script src="js/shaders/ConvolutionShader.js"></script>
		<script src="js/shaders/DotScreenShader.js"></script>
		<script src="js/shaders/HorizontalBlurShader.js"></script>
		<script src="js/shaders/RGBShiftShader.js"></script>
		<script src="js/shaders/FilmShader.js"></script>
		<script src="js/postprocessing/EffectComposer.js"></script>
		<script src="js/postprocessing/RenderPass.js"></script>
		<script src="js/postprocessing/MaskPass.js"></script>
		<script src="js/postprocessing/FilmPass.js"></script>
		<script src="js/postprocessing/BloomPass.js"></script>
		<script src="js/postprocessing/ShaderPass.js"></script>
        <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="http://mrdoob.github.com/three.js/examples/fonts/helvetiker_regular.typeface.js"></script>

		<script>

			var camera, scene, renderer, composer;
			var object, light, bscroll, scale, tense, action;
			

            scale = 1;
            tense = 1;
            action = true;
            use_bg = true;
			init();
			animate();

			function init() {
								
			    // INIT RENDERER
				renderer = new THREE.WebGLRenderer({ antialias: true });
				renderer.setSize( window.innerWidth, window.innerHeight );
				document.body.appendChild( renderer.domElement );
                renderer.setClearColor( 0, 1 );
                
				// THIS STUFF IS NEEDED FOR PP TO WORK
				renderer.autoClear = false;
				camera = new THREE.PerspectiveCamera( 70, window.innerWidth / window.innerHeight, 1, 1000 );
				camera.position.z = 400;

				scene = new THREE.Scene();

                // BACKGROUND
                bg = new THREE.Mesh(
                  new THREE.SphereGeometry(500,50,50),
                  new THREE.MeshBasicMaterial({color: 0x333333,map: THREE.ImageUtils.loadTexture(
                      "images/back.jpg"
                    ), depthWrite: false, side: THREE.BackSide })
                );

                scene.add(bg);
                


                // PARTICLE GENERATION (SUN)
                var particles = new THREE.Geometry();
                var pMaterial =
                  new THREE.ParticleBasicMaterial({
                    color: 0xFFFFFF,
                    size: 800,
                    map: THREE.ImageUtils.loadTexture(
                      "images/particle.png"
                    ),
                    blending: THREE.AdditiveBlending,
                    transparent: true,

                  });
                
                var particle = new THREE.Vertex(new THREE.Vector3(1, 100, -400));
                
                particles.vertices.push(particle);
            
                particleSystem =
                  new THREE.ParticleSystem(
                    particles,
                    pMaterial);
                scene.add(particleSystem);

                // OBJECT GENERATION
				object = new THREE.Object3D();
				scene.add( object );

				var geometry = new THREE.SphereGeometry( 1, 10, 10 );
				var material = new THREE.MeshPhongMaterial( { color: 0xf6f5f3, shading: THREE.FlatShading, blending: THREE.AdditiveBlending} );

				for ( var i = 0; i < 30; i ++ ) {

					var mesh = new THREE.Mesh( geometry, material );
					mesh.position.set( Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5 ).normalize();
					mesh.position.multiplyScalar( Math.random() * 150 );
					mesh.rotation.set( Math.random() * 2, Math.random() * 2, Math.random() * 2 );
					mesh.scale.x = mesh.scale.y = mesh.scale.z = Math.random() * 60;
					object.add( mesh );

				}
                // TEXT
                textto = $('#textto3d').val();
                if(textto.length<2)
                    textto = 'DUMMY TEXT PHO PHUN';
                var shape = new THREE.TextGeometry(textto);
                var wrapper = new THREE.MeshBasicMaterial({color: 0xaaaaaa, font: 'helvetiker'});
                var words = new THREE.Mesh(shape, wrapper);
                words.position.set(-100,-100,0);
                words.scale = new THREE.Vector3(0.2,0.2,0.05);
                scene.add(words);
                // LIGHT SETUP
				scene.add( new THREE.AmbientLight( 0 ) );

				light = new THREE.DirectionalLight( 0xebae75,1 );
				light.position.set( 1, 100, -400 );
				scene.add( light );

                plight = new THREE.PointLight( 0xffffff, 0, 1500 );
                plight.position.set( 10, 10, 550 );
                scene.add( plight );

				// postprocessing

				composer = new THREE.EffectComposer( renderer );
				composer.addPass( new THREE.RenderPass( scene, camera ) );


                // RENDER PASS
				var effect = new THREE.ShaderPass( THREE.RGBShiftShader );
				effect.uniforms[ 'amount' ].value = 0.0030;
				effect.renderToScreen = false;

                // FILM PASS
				var effectFilm = new THREE.FilmPass( 0.25, 1, 1048, false );
				effectFilm.renderToScreen = false;

                // BLOOM PASS
				var effectBloom = new THREE.BloomPass( 1.0 );

                // FXAA PASS
                var effectFXAA = new THREE.ShaderPass( THREE.FXAAShader );
                effectFXAA.uniforms[ 'resolution' ].value.set( 1 / window.innerWidth,  1 / window.innerHeight );
                effectFXAA.renderToScreen = true;
                
                // BLUR PASS
                var effectBlur = new THREE.ShaderPass( THREE.HorizontalBlurShader );
                effectBlur.renderToScreen = false;
                effectBlur.uniforms['h'].value = 0; // yep
                
				composer.addPass( effect );
				composer.addPass( effectBlur );
				composer.addPass( effectBloom );
				composer.addPass( effectFilm);

				composer.addPass( effectFXAA ); // render to screen - true
    		  
		
				window.addEventListener( 'resize', onWindowResize, false );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

            function fixPosition(scale) {
                var i;
                for(i=0;i<object.children.length;i++) {
                    object.children[i].position.setLength(scale);
                }
            }

			function animate() {

				requestAnimationFrame( animate );

				var time = Date.now();
				
				renderer.autoClear = false;
				renderer.clear();
				
				object.rotation.x += 0.005;
				object.rotation.y += 0.01;
                composer.passes[2].uniforms['h'].value=1/(Math.random()*6000); // index 2 is blur pass
                composer.passes[1].uniforms['amount'].value=1/(Math.random()*4000); // index 1 is rgb shift pass
				composer.render(0.15);	

			}

		</script>
    
        <a href="https://twitter.com/share" class="twitter-share-button" data-via="antistar999" data-hashtags="threejs">Tweet</a>
        
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    <audio id="au" src="https://dl.dropboxusercontent.com/u/43790496/dmtigr.mp3" autoplay loop></audio>
    
    <div id="slider"></div>
    
    <ul class="umenu">
        <li><a href="javascript:void(0)" onclick="plight.intensity = !plight.intensity">backlight</a></li>
        <li><a href="javascript:void(0)" class="pressed" onclick="light.intensity=!light.intensity;if(action){scene.remove(particleSystem);} else {scene.add(particleSystem);} action=!action">sun</a></li>
        <li><a href="javascript:void(0)" onclick="au.volume = !au.volume">mute</a></li>
        <li><a href="javascript:void(0)" class="pressed" onclick="if(use_bg){scene.remove(bg);}else{scene.add(bg);} use_bg = !use_bg">background</a></li>
    </ul>
    <script>
        au = document.getElementById('au');
        au.volume = 1;
        
        slider = $('#slider');
        
        slider.slider({min:80, max:380});
        
        slider.on( "slide", function( event, ui ) {
            fixPosition(ui.value);
        } );
        $('ul.umenu a').on('click',function(){
            $(this).toggleClass('pressed');
        });
        slider.slider( "value", 190 );
        fixPosition(190)
    </script>
    
	</body>
</html>
