(function(c){

	var node = document.getElementById('modal');

	var path = c.path;

	var viewModel = function(){
		this.title = ko.observable();
		this.body = ko.observable();
		this.footer = ko.observable();
		this.modalToShow = ko.observable(false);
		this.modalToShow.subscribe(function(v){
			if(v){
				//var dialog = node.getElementByClassName('modal-dialog');
				//console.log(dialog.className)
				console.log(node)
			}
			console.log(v)
		},this);
	};

	var modal = new viewModel;

	ko.applyBindings(modal,node);

	this.modal = modal;

})(_CONFIG_);