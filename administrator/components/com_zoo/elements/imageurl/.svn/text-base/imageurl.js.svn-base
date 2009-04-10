/* Copyright (C) 2009 Temperatio */

var ElementImageUrl = new Class({

	initialize: function(element, preview, options){
		this.element = $(element);
		this.preview = $(preview);
	},

	attachEvents: function() {
		var obj = this;

		this.element.addEvent('blur', function(){
			obj.preview.empty();
			new Element('img', { 'src': this.getProperty('value') }).injectInside(obj.preview);
		});	
	}

});

ElementImageUrl.implement(new Options);