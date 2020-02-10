//画背景
var drawBackground = {
	container : {},//容器
	camera : {},//相机
	scene : {},//场景
	renderer : {},//渲染
	mesh : {},//？
	geometry : {},//形状
	material : {},//材质
	mouseX : 0,
	mouseY : 0,
	containerHalfX : 0,
	containerHalfY : 0,
	requestId : undefined,
	startTime : Date.now(),
	stopTime : Date.now(),
	duringStopTime : 0,
	init : function(id) {
		this.setCamera(id);
		this.setRenderer();
		this.setScene();
		this.shader();
		this.addPlane();
		this.stopTime = Date.now();
	},
	//设置摄像机
	setCamera : function(id){
		this.container = document.getElementById(id);
		this.containerHalfX = this.container.offsetWidth/2;
		this.containerHalfY = this.container.offsetHeight/2;
		this.camera = new THREE.PerspectiveCamera( 20, this.container.offsetWidth / this.container.offsetHeight, 1, 3000 );
		this.camera.position.z = 6000;
	},
	//设置渲染器
	setRenderer : function(){
		var _this = this;
		var mask = document.createElement('div');
		mask.className = 'canvasafter';
	    this.renderer = new THREE.WebGLRenderer( { antialias: false , maxLight : 1} ); //webgl
    	//renderer = new THREE.CanvasRenderer(); //canvas
		this.renderer.setSize( this.container.offsetWidth, this.container.offsetHeight );
		this.container.appendChild( this.renderer.domElement );
		this.container.appendChild(mask);
		this.container.addEventListener( 'mousemove', function(e){_this.onDocumentMouseMove(e, _this)}, false );
	},
	//设置场景
	setScene : function(){
		this.scene = new THREE.Scene();
		this.geometry = new THREE.Geometry();
	},
	//编写着色器，不明觉厉
	shader : function(){
		var _this = this;
		var texture = THREE.ImageUtils.loadTexture( '/wp-content/themes/leonsblog/images/cloud10.png', null, function(){
			_this.startAnimate();
			_this.renderer.domElement.style.opacity = 1;
		} );
		texture.magFilter = THREE.LinearMipMapLinearFilter;
		texture.minFilter = THREE.LinearMipMapLinearFilter;
		var fog = new THREE.Fog( 0x87B5CE, - 100, 3000 );
		this.material = new THREE.ShaderMaterial( {
			uniforms: {
				"map": { type: "t", value: texture },
				"fogColor" : { type: "c", value: fog.color },
				"fogNear" : { type: "f", value: fog.near },
				"fogFar" : { type: "f", value: fog.far },
			},
			//顶点着色器
			vertexShader : "varying vec2 vUv;"+
							"void main() {"+
								"vUv = uv;"+
								"gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );"+
							"}",
			//片元着色器
			fragmentShader: "uniform sampler2D map;"+
							"uniform vec3 fogColor;"+
							"uniform float fogNear;"+
							"uniform float fogFar;"+
							"varying vec2 vUv;"+
							"void main() {"+
								"float depth = gl_FragCoord.z / gl_FragCoord.w;"+
								"float fogFactor = smoothstep( fogNear, fogFar, depth );"+
								"gl_FragColor = texture2D( map, vUv );"+
								"gl_FragColor.w *= pow( gl_FragCoord.z, 20.0 );"+
								"gl_FragColor = mix( gl_FragColor, vec4( fogColor, gl_FragColor.w ), fogFactor );"+
							"}",
			depthWrite: false,
			depthTest: false,
			transparent: true

		});
	},
	//向形状对象添加平面体并添加到场景中
	addPlane : function(){
		var plane = new THREE.Mesh( new THREE.PlaneGeometry( 64, 64 ) );
		for ( var i = 0; i < 8000; i++ ) {
			plane.position.x = Math.random() * 1000 - 500;
			plane.position.y = - Math.random() * Math.random() * 200 - 15;
			plane.position.z = i;
			plane.rotation.z = Math.random() * Math.PI;
			plane.scale.x = plane.scale.y = Math.random() * Math.random() * 1.5 + 0.5;
			THREE.GeometryUtils.merge( this.geometry, plane );
		}
		this.mesh = new THREE.Mesh( this.geometry, this.material );
		this.scene.add( this.mesh );
		//mesh = new THREE.Mesh( geometry, material );
		//mesh.position.z = - 4000;
		//scene.add( mesh );
	},
	onDocumentMouseMove : function( event, obj ) {
		this.mouseX = ( event.clientX - obj.containerHalfX ) * 0.25;
		this.mouseY = ( event.clientY - obj.containerHalfY ) * 0.15;
	},
	animate : function(obj) {
		this.requestId = requestAnimFrame( function(){
			obj.animate(obj);
		} );	
		var position = ( ( Date.now() - this.startTime - this.duringStopTime ) * 0.005 ) % 8000;
		this.camera.position.x += ( this.mouseX - this.camera.position.x ) * 0.005;
		this.camera.position.y += ( - this.mouseY - this.camera.position.y ) * 0.005;
		this.camera.position.z = - position + 8000;
		this.renderer.render( this.scene, this.camera );
	},
	startAnimate : function(){
		this.animate(this);
		this.duringStopTime += Date.now() - this.stopTime;
		console.log('动画共停止了' + (Date.now() - this.stopTime) + 'ms');
	},
	stopAnimate : function(){
		if (this.requestId) {
      		window.cancelAnimationFrame(this.requestId);
      		this.stopTime = Date.now();
      	 	this.requestId = undefined;
    	}
	}
}
