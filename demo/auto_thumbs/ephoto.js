(function(window){
	var photo = function(elm, opt) {
		var me = this;
		me.container = typeof elm == 'object' ? elm : document.getElementById(elm);
		me.childrens = me.container.children;
		me.opt = {
			defaultHeight : 150,
			margin : parseInt(getComputedStyle(me.childrens[0])['margin-left']) + parseInt(getComputedStyle(me.childrens[0])['margin-right'])
		}
		for (i in opt) me.opt[i] = opt[i];
		me.refleshLayout();
	}
	photo.prototype = {
		_getEveryLineNums : function () {
			var me 	     = this,
				l 		 = me.childrens.length,
				curWidth = 0,//计数
				start    = 0,//计数
				tmp      = {},
				r        = [];//返回值
			for (var i = 0 ; i < l; i++) {
				var o = me._imgMsg(me.childrens[i]);
				curWidth += me.opt.defaultHeight * o.w/o.h + me.opt.margin;
				if(curWidth >= me.maxWidth) {
					tmp = {
						start : start,
						end   : i,
						height: Math.round(me.maxWidth/curWidth * me.opt.defaultHeight)
					};
					r.push(tmp);
					start = i + 1;
					curWidth = 0;
				}
			}
			return r;
		},
		_imgMsg : function(obj){
			var	o = {
					w : obj.getAttribute('data-width'),
			  		h : obj.getAttribute('data-height')
				}
			return o;
		},
		_sort : function(data) {
			var me = this, max = me.childrens.length - 1;
			if(data.length > 0){
				for(var i in data) {
					me._setAttr(data[i].start, data[i].end, data[i].height, false);
				}
				if(data[i].end < max) {
					me._setAttr(data[i].end + 1, max, me.opt.defaultHeight, true);
				}
			} else{
				me._setAttr(0, max, me.opt.defaultHeight, true);
			}
		},
		_setAttr : function (start, end, h, last) {
			var me = this, item = me.childrens;
			var neW = me._getNeWidth(start, end, h, last, item);
			for(var i in neW) {
				me.__setAttrBox(neW[i], h, item[i]);
				me.__setAttrImg(neW[i], h, item[i]);
			}
		},
		__setAttrBox : function(w, h, box) {
			box.style.width   = w + 'px';
			box.style.height  = h + 'px';
		},
		__setAttrImg : function(w, h, box) {
			var me = this;
			var img = box.getElementsByTagName('img')[0];
			baseSize = me._imgMsg(box);//保持图片原有比例
			ratio = baseSize.w / baseSize.h;
			if(w/h < ratio) {
				w = ratio * h;
			} else {
				h = w / ratio;
			}
			img.src = img.getAttribute('_src');
			img.width   = w;
			img.height  = h;
		},
		_getNeWidth : function(s, e, h, last, item) {
			var me = this, o = {}, r = [], tmpW = 0, tmpS,
				dValue = e > s ? e - s + 1 : 1;
			//先算误差
			for(var i = s; i <= e; i++) {
				o = me._imgMsg(item[i]);
				r[i] = h/o.h * o.w;
				tmpW += r[i];
			}
			var gep = !last ? Math.round( (me.maxWidth - tmpW - me.opt.margin * dValue)/dValue ): 0;
			tmpW = 0;
			for(var i in r) {
				tmpS = Math.floor(r[i] + gep);//把误差分配给前n-1个，最后一个另算
				if(tmpW + tmpS + me.opt.margin > me.maxWidth || (Number(i) === e && tmpW + tmpS + me.opt.margin <= me.maxWidth && !last)) {
					tmpS = me.maxWidth - tmpW - me.opt.margin;
				}
				r[i] = tmpS;
				tmpW += tmpS + me.opt.margin;
			}
			return r;
		},
		refleshLayout : function () {
			var me = this;
			me.maxWidth = me.container.offsetWidth;
			me._sort(me._getEveryLineNums());
		}
	};
	window.ePhoto = photo;
})(window);