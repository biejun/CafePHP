(function(c){

	var node = document.getElementById('notify');

	var cookies = {
		type : cookie('__admin_notify_type__'),
		msg : cookie('__admin_notify_msg__')
	};

	var path = c.path;

	var viewModel = function(){
		this.type = ko.observable();
		this.msg = ko.observable();
		this.notifyToShow = ko.observable(false);
	};

	var notify = new viewModel;

	ko.applyBindings(notify,node);

	if( !!cookies.type && 'success|error'.indexOf(cookies.type) >= 0 ){

		notify.notifyToShow(true);
		notify.type(cookies.type);
		notify.msg(cookies.msg);

		setTimeout(function(){
			notify.notifyToShow(false);
			cookie('__admin_notify_type__',null,{path:path});
			cookie('__admin_notify_msg__',null,{path:path});
		},60*60);
	}

})(_CONFIG_);