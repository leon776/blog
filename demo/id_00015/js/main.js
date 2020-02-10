var $=function (id){return document.getElementById(id);}

function drawCanvas(obj){
	var bark = new Image();
	bark.src = obj.src;

	var canvas = obj.parentNode.getElementsByTagName("canvas")[0];
	if (!canvas.getContext) {
		return
	}
	else{
		var context = canvas.getContext("2d");
		context.save();
		//var canopyShadow=context.createLinearGradient(0,-50,0,0);
		context.translate(0, bark.height);
		context.scale(1, -1);
		context.drawImage(bark,0,0,bark.width,bark.height);
		context.restore();
		context.globalCompositeOperation = "destination-out";
		gradient = context.createLinearGradient(0, 0, 0, 70);
		gradient.addColorStop(0, "rgba(255, 255, 255, 0.5)");
		gradient.addColorStop(1, "rgba(255, 255, 255, 1.0)");
		context.fillStyle = gradient;
		context.fillRect(0, 0, bark.width,bark.height);
	}
}

var Controller = function (id){
	this.fObj =$(id);
	this.arrayA = this.fObj.getElementsByTagName("a");
	this.dWidth = document.documentElement.offsetWidth/2;
	this.dHeight = document.documentElement.offsetHeight/2;
	this.level = Math.ceil((this.arrayA.length/2))-1;
	this.init = function(){
	
		this.showFrist(this.arrayA[0]);	
		for (i=1;i<this.arrayA.length;i++)
		{
			if(i<(this.arrayA.length/2)){
				var nowLevel = i + 1;
				var pos = "right";
			}
			else{
				var nowLevel = this.arrayA.length-i +1;
				var pos = "left";
			}
			this.setLevel(this.arrayA[i],nowLevel,pos);
		}
		this.fObj.style.visibility = "visible";
		this.fObj.style.height = this.dHeight*2 +"px";
	}
	this.setLevel = function(obj,nowLevel,pos){
		var n =(nowLevel-1)/nowLevel;
		obj.style.opacity = 1.35-n;
		obj.style.zIndex = 100-nowLevel;
		if(pos == "right"){
			obj.style.left = (this.dWidth) + this.dWidth*(1-n)*(1+n/(nowLevel/1.25)) + "px";
		}
		else{
			obj.style.left = (this.dWidth) - this.dWidth*(1-n)*(1+n/(nowLevel/1.25)) +"px";
		}
		obj.style.top = (this.dHeight) - this.dHeight*n*0.18*nowLevel +"px";
		var canvas = obj.getElementsByTagName("canvas")[0];
		if (canvas.getContext) {
			obj.getElementsByTagName("canvas")[0].style.width = obj.getElementsByTagName("img")[0].style.width = obj.offsetWidth*(1-n)*(1.75) +"px";
			obj.getElementsByTagName("canvas")[0].style.top = obj.getElementsByTagName("canvas")[0].style.height = obj.offsetHeight*(1-n)*(1.75)+"px";
		}
		else{
			obj.getElementsByTagName("img")[0].style.width =obj.offsetWidth*(1-n)*(1.75) +"px";

		}
		obj.style.marginLeft = -obj.offsetWidth*(1-n)*(1.75)/2 +"px"
		obj.style.marginTop = -obj.offsetHeight*(1-n)*(1.75)/2 +"px"
	}
	this.showFrist = function(obj){
		obj.style.left = this.dWidth + "px";
		obj.style.top = this.dHeight + "px";
		obj.style.marginLeft =-obj.offsetWidth/2 + "px";
		obj.style.marginTop =-obj.offsetHeight/2 + "px";
		obj.getElementsByTagName("canvas")[0].style.width = obj.offsetWidth +"px";
		obj.getElementsByTagName("canvas")[0].style.top = obj.getElementsByTagName("canvas")[0].style.height  = obj.offsetHeight+"px";
		obj.style.zIndex = 100;
	}
	this.change = function(a,d){
		if(d=="r"){
			var fstCss = a[0].style.cssText;
			var fstImgCss = a[0].getElementsByTagName("img")[0].style.cssText;
			var fstCanvasCss = a[0].getElementsByTagName("canvas")[0].style.cssText;
			for (i=0;i<(a.length-1);i++)
			{
				var j = Number(i+1);
				a[i].style.cssText = a[j].style.cssText;
				a[i].getElementsByTagName("img")[0].style.cssText = a[j].getElementsByTagName("img")[0].style.cssText;
				a[i].getElementsByTagName("canvas")[0].style.cssText = a[j].getElementsByTagName("canvas")[0].style.cssText;
			}
			a[a.length-1].style.cssText = fstCss;
			a[a.length-1].getElementsByTagName("img")[0].style.cssText = fstImgCss;
			a[a.length-1].getElementsByTagName("canvas")[0].style.cssText = fstCanvasCss;
		}
		else{
			var fstCss = a[a.length-1].style.cssText;
			var fstImgCss = a[a.length-1].getElementsByTagName("img")[0].style.cssText;
			var fstCanvasCss = a[a.length-1].getElementsByTagName("canvas")[0].style.cssText;
			for (i=(a.length-1);i>0;i--)
			{
				var j = Number(i-1);
				a[i].style.cssText = a[j].style.cssText;
				a[i].getElementsByTagName("img")[0].style.cssText = a[j].getElementsByTagName("img")[0].style.cssText;
				a[i].getElementsByTagName("canvas")[0].style.cssText = a[j].getElementsByTagName("canvas")[0].style.cssText;
			}
			a[0].style.cssText = fstCss;
			a[0].getElementsByTagName("img")[0].style.cssText = fstImgCss;
			a[0].getElementsByTagName("canvas")[0].style.cssText = fstCanvasCss; 
		}
	}
}
window.onload  = function(){
	
	var carouse2 = new Controller("carousel1");
	carouse2.init();
	$("right-but").onclick = function(){
		carouse2.change(carouse2.arrayA,"r");
	}
	$("left-but").onclick = function(){
		carouse2.change(carouse2.arrayA,"l");
	}
}