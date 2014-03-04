/*
 * iosSlider - http://iosscripts.com/iosslider/
 * 
 * A jQuery Horizontal Slider for iPhone/iPad Safari 
 * This plugin turns any wide element into a touch enabled horizontal slider.
 * 
 * Copyright (c) 2012 Marc Whitbread
 * 
 * Version: v1.0.27 (08/02/2012)
 * Minimum requirements: jQuery v1.4+
 * 
 * Advanced requirements:
 * 1) jQuery bind() click event override on slide requires jQuery v1.6+
 *
 * Terms of use:
 *
 * 1) iosSlider is licensed under the Creative Commons � Attribution-NonCommercial 3.0 License.
 * 2) You may use iosSlider free for personal or non-profit purposes, without restriction. 
 *	  Attribution is not required but always appreciated. For commercial projects, you 
 *	  must purchase a license. You may download and play with the script before deciding to 
 *	  fully implement it in your project. Making sure you are satisfied, and knowing iosSlider 
 *	  is the right script for your project is paramount.
 * 3) You are not permitted to make the resources found on iosscripts.com available for 
 *    distribution elsewhere "as is" without prior consent. If you would like to feature 
 *    iosSlider on your site, please do not link directly to the resource zip files. Please 
 *    link to the appropriate page on iosscripts.com where users can find the download.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 */
 
(function(a){var T=0,K=0,M=0,F=0,D="ontouchstart"in window,ka="onorientationchange"in window,L=!1,U=!1,V=!1,O="pointer",ba="pointer",W=[],P=[],ca=[],da=[],Q=[],i=[],X=[],f={showScrollbar:function(e,c){e.scrollbarHide&&a("."+c).css({opacity:e.scrollbarOpacity,filter:"alpha(opacity:"+100*e.scrollbarOpacity+")"})},hideScrollbar:function(a,c,b,r,d,l,j,i,s,k){if(a.scrollbar&&a.scrollbarHide)for(var h=b;h<b+25;h++)c[c.length]=f.hideScrollbarIntervalTimer(10*h,r[b],(b+24-h)/24,d,l,j,i,s,k,a)},hideScrollbarInterval:function(e, c,b,r,d,l,j,i,s){F=-1*e/b*(l-j-i-d);f.setSliderOffset("."+r,F);a("."+r).css({opacity:s.scrollbarOpacity*c,filter:"alpha(opacity:"+100*s.scrollbarOpacity*c+")"})},slowScrollHorizontalInterval:function(e,c,b,r,d,l,j,z,s,k,h,v,A,p,m,t){newChildOffset=f.calcActiveOffset(t,c,0,h,b,l,A,k);if(newChildOffset!=i[m]&&""!=t.onSlideChange)t.onSlideChange(new f.args(t,e,a(e).children(":eq("+k+")"),k%A));i[m]=newChildOffset;c=Math.floor(c);f.setSliderOffset(e,c);t.scrollbar&&(F=Math.floor(-1*c/b*(j-z-d)),e=d-s, 0<=c?(e=d-s- -1*F,f.setSliderOffset(a("."+r),0)):(c<=-1*b+1&&(e=j-z-s-F),f.setSliderOffset(a("."+r),F)),a("."+r).css({width:e+"px"}))},slowScrollHorizontal:function(e,c,b,r,d,l,j,z,s,k,h,v,A,p,m,t,w,u){var l=[],g=f.getSliderOffset(e,"x"),x=0,n=25/1024*z;frictionCoefficient=u.frictionCoefficient;elasticFrictionCoefficient=u.elasticFrictionCoefficient;snapFrictionCoefficient=u.snapFrictionCoefficient;snapToChildren=u.snapToChildren;5<d&&snapToChildren?x=1:-5>d&&snapToChildren&&(x=-1);d<-1*n?d=-1*n: d>n&&(d=n);a(e)[0]!==a(w)[0]&&(x*=-1,d*=-2);n=f.getAnimationSteps(u,d,g,b,0,v);w=f.calcActiveOffset(u,n[n.length-1],x,v,b,z,p,i[A]);u.infiniteSlider&&(v[w]>v[t+1]+z&&(w+=t),v[w]<v[2*t-1]-z&&(w-=t));if(n[n.length-1]<v[w]&&0>x||n[n.length-1]>v[w]&&0<x||!snapToChildren)for(;1<d||-1>d;){d*=frictionCoefficient;g+=d;if(0<g||g<-1*b)d*=elasticFrictionCoefficient,g+=d;l[l.length]=g}if(snapToChildren||0<g||g<-1*b){for(;g<v[w]-0.5||g>v[w]+0.5;)g=(g-v[w])*snapFrictionCoefficient+v[w],l[l.length]=g;l[l.length]= v[w]}d=1;0!=l.length%2&&(d=0);u.infiniteSlider&&(w=w%t+t);for(g=0;g<c.length;g++)clearTimeout(c[g]);x=0;for(g=d;g<l.length;g+=2)if(u.infiniteSlider&&l[g]<v[2*t]+z&&(l[g]-=v[t]),g==d||1<Math.abs(l[g]-x)||g>=l.length-2)x=l[g],c[c.length]=f.slowScrollHorizontalIntervalTimer(10*g,e,l[g],b,r,j,z,s,k,h,w,v,m,p,t,A,u);c[c.length]=f.onSlideCompleteTimer(10*(g+1),u,e,a(e).children(":eq("+w+")"),w%p,A);Q[A]=c;f.hideScrollbar(u,c,g,l,b,r,j,z,k,h)},onSlideComplete:function(e,c,b,r,d){if(W[d]!=r&&""!=e.onSlideComplete)e.onSlideComplete(new f.args(e, a(c),b,r));W[d]=r},getSliderOffset:function(e,c){var b=0,c="x"==c?4:5;D&&L?(b=a(e).css("-webkit-transform").split(","),b=parseInt(b[c],10)):b=parseInt(a(e).css("left"),10);return b},setSliderOffset:function(e,c){D&&L?a(e).css({webkitTransform:"matrix(1,0,0,1,"+c+",0)"}):a(e).css({left:c+"px"})},setBrowserInfo:function(){null!=navigator.userAgent.match("WebKit")?(L=!0,O="-webkit-grab",ba="-webkit-grabbing"):null!=navigator.userAgent.match("Gecko")?(O="move",ba="-moz-grabbing"):null!=navigator.userAgent.match("MSIE 7")? U=!0:null!=navigator.userAgent.match("MSIE 8")?V=!0:navigator.userAgent.match("MSIE 9")},getAnimationSteps:function(a,c,b,f,d){var l=[];for(1>=c&&0<=c?c=-2:-1<=c&&0>=c&&(c=2);1<c||-1>c;){c*=a.frictionCoefficient;b+=c;if(b>d||b<-1*f)c*=a.elasticFrictionCoefficient,b+=c;l[l.length]=b}activeChildOffset=0;return l},calcActiveOffset:function(a,c,b,f,d,l,j,i){for(var s=!1,a=[],k,d=0;d<f.length;d++)f[d]<=c&&f[d]>c-l&&(!s&&f[d]!=c&&(a[a.length]=f[d-1]),a[a.length]=f[d],s=!0);0==a.length&&(a[0]=f[f.length- 1]);for(d=s=0;d<a.length;d++){var h=Math.abs(c-a[d]);h<l&&(s=a[d],l=h)}for(d=0;d<f.length;d++)s==f[d]&&(k=d);0>b&&k%j==i%j?(k=i+1,k>=f.length&&(k=f.length-1)):0<b&&k%j==i%j&&(k=i-1,0>k&&(k=0));return k},changeSlide:function(e,c,b,r,d,l,j,i,s,k,h,v,A,p,m,t){f.autoSlidePause(v);for(var w=0;w<b.length;w++)clearTimeout(b[w]);var u=Math.ceil(t.autoSlideTransTimer/10)+1,g=f.getSliderOffset(c,"x");t.infiniteSlider&&g>h[m+1]+j&&e==2*m-2&&(g-=p);var w=h[e]-g,x=[],n;f.showScrollbar(t,d);for(var q=0;q<=u;q++)n= q,n/=u,n--,n=g+w*(Math.pow(n,5)+1),t.infiniteSlider&&(n>h[m+1]+j&&(n-=p),n<h[2*m-1]-j&&(n+=p)),x[x.length]=n;t.infiniteSlider&&(e=e%m+m);for(q=u=0;q<x.length;q++){t.infiniteSlider&&x[q]<h[2*m]+j&&(x[q]-=h[m]);if(0==q||1<Math.abs(x[q]-u)||q>=x.length-2)u=x[q],b[q]=f.slowScrollHorizontalIntervalTimer(10*(q+1),c,x[q],r,d,l,j,i,s,k,e,h,p,A,m,v,t);if(0==q&&""!=t.onSlideStart)t.onSlideStart(new f.args(t,c,a(c).children(":eq("+e+")"),e%A))}0!=w&&(b[b.length]=f.onSlideCompleteTimer(10*(q+1),t,c,a(c).children(":eq("+ e+")"),e%A,v));Q[v]=b;f.hideScrollbar(t,b,q,x,r,d,l,j,s,k);f.autoSlide(c,b,r,d,l,j,i,s,k,h,v,A,p,m,t)},autoSlide:function(a,c,b,r,d,l,j,z,s,k,h,v,A,p,m){if(!m.autoSlide)return!1;f.autoSlidePause(h);P[h]=setTimeout(function(){!m.infiniteSlider&&i[h]>k.length-1&&(i[h]-=p);f.changeSlide(m.infiniteSlider?i[h]+1:(i[h]+1)%p,a,c,b,r,d,l,j,z,s,k,h,v,A,p,m);f.autoSlide(a,c,b,r,d,l,j,z,s,k,h,v,A,p,m)},m.autoSlideTimer+m.autoSlideTransTimer)},autoSlidePause:function(a){clearTimeout(P[a])},isUnselectable:function(e, c){return""!=c.unselectableSelector&&1==a(e).closest(c.unselectableSelector).size()?!0:!1},slowScrollHorizontalIntervalTimer:function(a,c,b,r,d,l,j,i,s,k,h,v,A,p,m,t,w){return setTimeout(function(){f.slowScrollHorizontalInterval(c,b,r,d,l,j,i,s,k,h,v,A,p,m,t,w)},a)},onSlideCompleteTimer:function(a,c,b,i,d,l){return setTimeout(function(){f.onSlideComplete(c,b,i,d,l)},a)},hideScrollbarIntervalTimer:function(a,c,b,i,d,l,j,z,s,k){return setTimeout(function(){f.hideScrollbarInterval(c,b,i,d,l,j,z,s,k)}, a)},args:function(f,c,b,i){this.settings=f;this.sliderObject=c;this.sliderContainerObject=a(c).parent();this.currentSlideObject=b;this.currentSlideNumber=i;this.numberOfSlides=a(c).parent().data("iosslider").numberOfSlides},preventDrag:function(a){a.preventDefault()},preventClick:function(a){a.stopImmediatePropagation();return!1},enableClick:function(){return!0}};f.setBrowserInfo();var H={init:function(e,c){var b=a.extend(!0,{elasticPullResistance:0.6,frictionCoefficient:0.92,elasticFrictionCoefficient:0.6, snapFrictionCoefficient:0.92,snapToChildren:!1,startAtSlide:1,scrollbar:!1,scrollbarDrag:!1,scrollbarHide:!0,scrollbarLocation:"top",scrollbarContainer:"",scrollbarOpacity:0.4,scrollbarHeight:"4px",scrollbarBorder:"0",scrollbarMargin:"5px",scrollbarBackground:"#000",scrollbarBorderRadius:"100px",scrollbarShadow:"0 0 0 #000",scrollbarElasticPullResistance:0.9,desktopClickDrag:!1,responsiveSlideContainer:!0,responsiveSlides:!0,navSlideSelector:"",navPrevSelector:"",navNextSelector:"",autoSlideToggleSelector:"", autoSlide:!1,autoSlideTimer:5E3,autoSlideTransTimer:750,infiniteSlider:!1,stageCSS:{position:"relative",top:"0",left:"0",overflow:"hidden",zIndex:1},sliderCSS:{overflow:"hidden"},unselectableSelector:"",onSliderLoaded:"",onSlideStart:"",onSlideChange:"",onSlideComplete:""},e);void 0==c&&(c=this);return a(c).each(function(){function c(){f.autoSlidePause(d);a(u).css("width","");a(u).css("height","");a(o).css("width","");ea=a(o).children();a(ea).css("width","");j=0;y=[];t=a(u).parent().width();g=a(u).outerWidth(!0); b.responsiveSlideContainer&&(g=a(u).outerWidth(!0)>t?t:a(u).outerWidth(!0));a(u).css({position:b.stageCSS.position,top:b.stageCSS.top,left:b.stageCSS.left,overflow:b.stageCSS.overflow,zIndex:b.stageCSS.zIndex,webkitPerspective:1E3,webkitBackfaceVisibility:"hidden",width:g});a(b.unselectableSelector).css({cursor:"default"});b.responsiveSlides&&a(ea).each(function(){var b=a(this).outerWidth(!0),b=b>g?g+-1*(a(this).outerWidth(!0)-a(this).width()):a(this).width();a(this).css({width:b})});a(o).children().each(function(b){a(this).css({"float":"left"}); y[b]=-1*j;j+=a(this).outerWidth(!0)});for(var Y=0;Y<y.length&&!(y[Y]<=-1*(j-g));Y++)ia=Y;y.splice(ia+1,y.length);y[y.length]=-1*(j-g);j-=g;a(o).css({position:"relative",overflow:b.sliderCSS.overflow,cursor:O,webkitPerspective:1E3,webkitBackfaceVisibility:"hidden",width:j+g+"px"});w=a(u).parent().height();x=a(u).height();b.responsiveSlideContainer&&(x=a(u).height()>w?w:a(u).height());a(u).css({height:x});f.setSliderOffset(o,y[i[d]]);if(0>=j)return a(o).css({cursor:"default"}),!1;!D&&!b.desktopClickDrag&& a(o).css({cursor:"default"});b.scrollbar&&(a("."+k).css({margin:b.scrollbarMargin,overflow:"hidden",display:"none"}),a("."+k+" ."+h).css({border:b.scrollbarBorder}),n=parseInt(a("."+k).css("marginLeft"))+parseInt(a("."+k).css("marginRight")),q=parseInt(a("."+k+" ."+h).css("borderLeftWidth"),10)+parseInt(a("."+k+" ."+h).css("borderRightWidth"),10),p=""!=b.scrollbarContainer?a(b.scrollbarContainer).width():g,m=(p-n)/C,b.scrollbarHide||(L=b.scrollbarOpacity),a("."+k).css({position:"absolute",left:0, width:p-n+"px",margin:b.scrollbarMargin}),"top"==b.scrollbarLocation?a("."+k).css("top","0"):a("."+k).css("bottom","0"),a("."+k+" ."+h).css({borderRadius:b.scrollbarBorderRadius,background:b.scrollbarBackground,height:b.scrollbarHeight,width:m-q+"px",minWidth:b.scrollbarHeight,border:b.scrollbarBorder,webkitPerspective:1E3,webkitBackfaceVisibility:"hidden",position:"relative",opacity:L,filter:"alpha(opacity:"+100*L+")",boxShadow:b.scrollbarShadow}),f.setSliderOffset(a("."+k+" ."+h),Math.floor(-1* y[i[d]]/j*(p-n-m))),a("."+k).css({display:"block"}),v=a("."+k+" ."+h),A=a("."+k));b.scrollbarDrag&&a("."+k+" ."+h).css({cursor:O});b.infiniteSlider&&(E=(j+g)/3);""!=b.navSlideSelector&&a(b.navSlideSelector).each(function(c){a(this).css({cursor:"pointer"});a(this).unbind("click.iosSliderEvent").bind("click.iosSliderEvent",function(){var a=c;b.infiniteSlider&&(a=c+B);f.changeSlide(a,o,e,j,h,m,g,p,n,q,y,d,B,E,C,b)})});""!=b.navPrevSelector&&(a(b.navPrevSelector).css({cursor:"pointer"}),a(b.navPrevSelector).unbind("click.iosSliderEvent").bind("click.iosSliderEvent", function(){(i[d]>0||b.infiniteSlider)&&f.changeSlide(i[d]-1,o,e,j,h,m,g,p,n,q,y,d,B,E,C,b)}));""!=b.navNextSelector&&(a(b.navNextSelector).css({cursor:"pointer"}),a(b.navNextSelector).unbind("click.iosSliderEvent").bind("click.iosSliderEvent",function(){(i[d]<y.length-1||b.infiniteSlider)&&f.changeSlide(i[d]+1,o,e,j,h,m,g,p,n,q,y,d,B,E,C,b)}));b.autoSlide&&((""!=b.autoSlideToggleSelector&&(a(b.autoSlideToggleSelector).css({cursor:"pointer"}),a(b.autoSlideToggleSelector).unbind("click.iosSliderEvent").bind("click.iosSliderEvent", function(){if(N){f.autoSlide(o,e,j,h,m,g,p,n,q,y,d,B,E,C,b);N=false;a(b.autoSlideToggleSelector).removeClass("on")}else{f.autoSlidePause(d);N=true;a(b.autoSlideToggleSelector).addClass("on")}})),N||f.autoSlide(o,e,j,h,m,g,p,n,q,y,d,B,E,C,b),D)?a(u).bind("touchend.iosSliderEvent",function(){N||f.autoSlide(o,e,j,h,m,g,p,n,q,y,d,B,E,C,b)}):(a(u).bind("mouseenter.iosSliderEvent",function(){f.autoSlidePause(d)}),a(u).bind("mouseleave.iosSliderEvent",function(){N||f.autoSlide(o,e,j,h,m,g,p,n,q,y,d,B,E, C,b)})));a(u).data("iosslider",{obj:ja,settings:b,scrollerNode:o,numberOfSlides:C,sliderNumber:d,childrenOffsets:y,sliderMax:j,scrollbarClass:h,scrollbarWidth:m,scrollbarStageWidth:p,stageWidth:g,scrollMargin:n,scrollBorder:q,infiniteSliderOffset:B,infiniteSliderWidth:E});return!0}T++;var d=T,e=[];ca[d]=b;var j,z=[0,0],s=[0,0],k="scrollbarBlock"+T,h="scrollbar"+T,v,A,p,m,t,w,u=a(this),g,x,n,q,H;i[d]=b.startAtSlide-1;var R=-1,y,L=0,G=0,P=0,o=a(this).children(":first-child"),ea,C=a(o).children().size(), I=!1,ia=0,fa=!1,Z=void 0,E,B=C;W[d]=-1;var N=!1;da[d]=!1;var $,aa=!1;X[d]=!1;Q[d]=[];b.scrollbarDrag&&(b.scrollbar=!0,b.scrollbarHide=!1);var ja=a(this);if(void 0!=ja.data("iosslider"))return!0;a(this).find("img").bind("dragstart.iosSliderEvent",function(a){a.preventDefault()});b.infiniteSlider&&(b.scrollbar=!1,a(o).children().clone(!0,!0).prependTo(o).clone(!0,!0).appendTo(o),B=C);b.scrollbar&&(""!=b.scrollbarContainer?a(b.scrollbarContainer).append("<div class = '"+k+"'><div class = '"+h+"'></div></div>"): a(o).parent().append("<div class = '"+k+"'><div class = '"+h+"'></div></div>"));if(!c())return!0;b.infiniteSlider&&(i[d]+=B,f.setSliderOffset(o,y[i[d]]));a(this).find("a").bind("mousedown",f.preventDrag);a(this).find("[onclick]").bind("click",f.preventDrag).each(function(){a(this).data("onclick",this.onclick)});if(""!=b.onSliderLoaded)b.onSliderLoaded(new f.args(b,o,a(o).children(":eq("+i[d]+")"),i[d]%B));W[d]=i[d]%B;if(ca[d].responsiveSlides||ca[d].responsiveSlideContainer){var J=ka?"orientationchange": "resize";a(window).bind(J+".iosSliderEvent",function(){if(!c())return true})}if(D||b.desktopClickDrag){var ga=D?"touchstart.iosSliderEvent":"mousedown.iosSliderEvent",S=a(o),J=a(o),ha=!1;b.scrollbarDrag&&(S=S.add(v),J=J.add(A));a(S).bind(ga,function(c){if(X[d])return true;if(ha=f.isUnselectable(c.target,b))return true;$=a(this)[0]===a(v)[0]?v:o;if(!U&&!V)c=c.originalEvent;f.autoSlidePause(d);if(D){eventX=c.touches[0].pageX;eventY=c.touches[0].pageY}else{window.getSelection?window.getSelection().empty? window.getSelection().empty():window.getSelection().removeAllRanges&&window.getSelection().removeAllRanges():document.selection&&document.selection.empty();eventX=c.pageX;eventY=c.pageY;fa=true;Z=o;a(this).css({cursor:ba})}z=[0,0];s=[0,0];K=0;I=false;for(c=0;c<e.length;c++)clearTimeout(e[c]);c=f.getSliderOffset(o,"x");b.infiniteSlider&&i[d]%C==0&&a(o).children().each(function(b){b%C==0&&b!=i[d]&&a(this).replaceWith(function(){return a(o).children(":eq("+i[d]+")").clone(true)})});if(c>0){f.setSliderOffset(a("."+ h),0);a("."+h).css({width:m-q+"px"})}else if(c<j*-1){c=j*-1;f.setSliderOffset(o,c);f.setSliderOffset(a("."+h),p-n-m);a("."+h).css({width:m-q+"px"})}G=(f.getSliderOffset(this,"x")-eventX)*-1;f.getSliderOffset(this,"y");z[1]=eventX;s[1]=eventY});ga=D?"touchmove.iosSliderEvent":"mousemove.iosSliderEvent";a(J).bind(ga,function(c){if(!U&&!V)c=c.originalEvent;if(ha)return true;var e=0;D||(window.getSelection?window.getSelection().empty?window.getSelection().empty():window.getSelection().removeAllRanges&& window.getSelection().removeAllRanges():document.selection&&document.selection.empty());if(D){eventX=c.touches[0].pageX;eventY=c.touches[0].pageY}else{eventX=c.pageX;eventY=c.pageY;if(!fa)return false}if(b.infiniteSlider){f.getSliderOffset(o,"x")>y[C+1]+g&&(G=G+E);f.getSliderOffset(o,"x")<y[C*2-1]-g&&(G=G-E)}z[0]=z[1];z[1]=eventX;K=(z[1]-z[0])/2;s[0]=s[1];s[1]=eventY;M=(s[1]-s[0])/2;if(!I&&b.onSlideStart!="")b.onSlideStart(new f.args(b,o,a(o).children(":eq("+i[d]+")"),i[d]%B));if((M>3||M<-3)&&D&& !I)aa=true;if((K>5||K<-5)&&D){c.preventDefault();I=true}else D||(I=true);if(I&&!aa){var k=f.getSliderOffset(o,"x"),r=a(this)[0]===a(A)[0]?-1*j/(p-n-m):1,l=a(this)[0]===a(A)[0]?b.scrollbarElasticPullResistance:b.elasticPullResistance;if(D){P!=c.touches.length&&(G=k*-1+eventX);P=c.touches.length}k>0&&(e=(G-eventX)*r*l/r);k<j*-1&&(e=(j+(G-eventX)*-1*r)*l*-1/r);f.setSliderOffset(o,(G-eventX-e)*-1*r);if(b.scrollbar){f.showScrollbar(b,h);F=Math.floor((G-eventX-e)/j*(p-n-m)*r);r=m;if(k>=0){r=m-q-F*-1;f.setSliderOffset(a("."+ h),0);a("."+h).css({width:r+"px"})}else if(k<=j*-1+1){r=p-n-q-F;f.setSliderOffset(a("."+h),F);a("."+h).css({width:r+"px"})}else f.setSliderOffset(a("."+h),F)}if(D)H=c.touches[0].pageX}R=f.calcActiveOffset(b,(G-eventX-e)*-1,0,y,j,g,B,void 0);if(R!=i[d]&&b.onSlideChange!=""){i[d]=R;b.onSlideChange(new f.args(b,o,a(o).children(":eq("+R+")"),R%B))}});a(S).bind("touchend.iosSliderEvent",function(a){a=a.originalEvent;if(ha)return true;if(a.touches.length!=0)for(var c=0;c<sizeof(a.touches.length);c++)a.touches[c].pageX== H&&f.slowScrollHorizontal(o,e,j,h,K,M,m,g,p,n,q,y,d,B,E,C,$,b);else f.slowScrollHorizontal(o,e,j,h,K,M,m,g,p,n,q,y,d,B,E,C,$,b);aa=false});if(!D){J=a(window);if(V||U)J=a(document);a(J).bind("mouseup.iosSliderEvent",function(){I?a(o).children(":eq("+i[d]+")").find("a").unbind("click.disableClick").bind("click.disableClick",f.preventClick):a(o).children(":eq("+i[d]+")").find("a").unbind("click.disableClick").bind("click.disableClick",f.enableClick);a(o).children(":eq("+i[d]+")").find("[onclick]").each(function(){this.onclick= function(b){if(I)return false;a(this).data("onclick").call(this,b||window.event)}});parseFloat(a().jquery)>=1.6&&a(o).children(":eq("+i[d]+")").find("*").each(function(){var b=a(this).data("events");if(b!=void 0&&b.click!=void 0&&b.click[0].namespace!="iosSliderEvent"){if(!I)return false;a(this).one("click.disableClick",f.preventClick);var b=a(this).data("events").click,c=b.pop();b.splice(0,0,c)}});if(!da[d]){a(S).css({cursor:O});fa=false;if(Z==void 0)return false;f.slowScrollHorizontal(Z,e,j,h,K, M,m,g,p,n,q,y,d,B,E,C,$,b);Z=void 0}aa=false})}}})},destroy:function(e,c){void 0==c&&(c=this);return a(c).each(function(){var b=a(this),c=b.data("iosslider");if(void 0==c)return!1;void 0==e&&(e=!0);f.autoSlidePause(c.sliderNumber);da[c.sliderNumber]=!0;a(this).unbind(".iosSliderEvent");a(this).children(":first-child").unbind(".iosSliderEvent");a(this).children(":first-child").children().unbind(".iosSliderEvent");e&&(a(this).attr("style",""),a(this).children(":first-child").attr("style",""),a(this).children(":first-child").children().attr("style", ""),a(c.settings.navSlideSelector).attr("style",""),a(c.settings.navPrevSelector).attr("style",""),a(c.settings.navNextSelector).attr("style",""),a(c.settings.autoSlideToggleSelector).attr("style",""),a(c.settings.unselectableSelector).attr("style",""));c.settings.infiniteSlider&&(a(this).children(":first-child").html(),a(this).children(":first-child").html(a(this).children(":first-child").children(":nth-child(-n+"+c.numberOfSlides+")").clone(!0)));c.settings.scrollbar&&a(".scrollbarBlock"+c.sliderNumber).remove(); for(var c=Q[c.sliderNumber],d=0;d<c.length;d++)clearTimeout(c[d]);b.removeData("iosslider")})},update:function(e){void 0==e&&(e=this);return a(e).each(function(){var c=a(this).data("iosslider");if(void 0==c)return!1;H.destroy(!1,this);c.settings.startAtSlide=i[c.sliderNumber]+1;H.init(c.settings,this)})},addSlide:function(e,c){return this.each(function(){var b=a(this).data("iosslider");if(void 0==b)return!1;c<=b.numberOfSlides?a(b.scrollerNode).children(":eq("+(c-1)+")").before(e):a(b.scrollerNode).children(":eq("+ (c-2)+")").after(e);i[b.sliderNumber]>c-2&&i[b.sliderNumber]++;H.update(this)})},removeSlide:function(e){return this.each(function(){var c=a(this).data("iosslider");if(void 0==c)return!1;a(c.scrollerNode).children(":eq("+(e-1)+")").remove();i[c.sliderNumber]>e-1&&i[c.sliderNumber]--;H.update(this)})},goToSlide:function(e,c){void 0==c&&(c=this);return a(c).each(function(){var b=a(this).data("iosslider");if(void 0==b)return!1;e=(e-1)%b.numberOfSlides;if(b.settings.infiniteSlider){var c=0.5*b.numberOfSlides, d=e<(c+i[b.sliderNumber])%b.numberOfSlides?1:-1;e+=b.infiniteSliderOffset;0>d&&i[b.sliderNumber]%b.numberOfSlides<c&&(e-=b.infiniteSliderOffset);0<d&&i[b.sliderNumber]%b.numberOfSlides>c&&(e+=b.infiniteSliderOffset)}f.changeSlide(e,a(b.scrollerNode),Q[b.sliderNumber],b.sliderMax,b.scrollbarClass,b.scrollbarWidth,b.stageWidth,b.scrollbarStageWidth,b.scrollMargin,b.scrollBorder,b.childrenOffsets,b.sliderNumber,b.infiniteSliderOffset,b.infiniteSliderWidth,b.numberOfSlides,b.settings);i[b.sliderNumber]= e})},lock:function(){return this.each(function(){var e=a(this).data("iosslider");if(void 0==e)return!1;X[e.sliderNumber]=!0})},unlock:function(){return this.each(function(){var e=a(this).data("iosslider");if(void 0==e)return!1;X[e.sliderNumber]=!1})}};a.fn.iosSlider=function(e){if(H[e])return H[e].apply(this,Array.prototype.slice.call(arguments,1));if("object"===typeof e||!e)return H.init.apply(this,arguments);a.error("invalid method call!")}})(jQuery);