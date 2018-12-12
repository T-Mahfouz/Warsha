function changeActiveClass(thisItem){
	elemenstArray = document.getElementsByClassName("navigate-item");
	elemenstArray = [].slice.call(elemenstArray);
	for (var i = 0; i < elemenstArray.length; ++i)
		elemenstArray[i].classList.remove("active");

	thisItem.classList.add("active");
}

function deleteAdmin(admin_id){
	if(confirm('هل تريد حذف هذا المدير؟')){
		$.ajax({
			method:'POST',
			url:admin_delete_admin_url,
			data:{admin_id:admin_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما أثناء الحذف');
		});
	}
}
function changeAdminStatus(admin){
	$('#status_admin_id').val(admin.id);
	$('#change_admin_status_modal').modal();
}
function editAdmin(admin,categories){

	$('#admin_id').val(admin.id);
	var cats = "";
	categories.forEach(function(item){
		cats += "<option value='"+item.id+"' ";
		admin.categories.forEach(function(item2){
			if(item.id == item2)
				cats += "selected ";
		});
		cats += ">"+ item.name_en+" - "+item.name_ar+"</option>";
	});
	$('#admin_categories').html(cats);
	$('#edit_admin_modal').modal();
}
function addAdmin(categoy){
	$('#add_admin_modal').modal();
}

function dismiss(id){
		$("#delete_category_"+id+"_modal").modal('hide');
}
function deleteCategory(id){
		$.ajax({
				method: 'POST',
				url: delete_category_url,
				data: {category_id: parseInt(id),_token:token}
		})
		.done(response => {
				$("#child_item_"+id).css('display','none');
				$("#delete_category_"+id+"_modal").modal('hide');
		})
		.fail(error => {
				console.error(error)
		})
}

function deleteUser(id){
	$('#delete_user_id').val(id);
	$('#delete_user_modal').modal();
}
function changeUserStatus(user){
	$('#status_user_id').val(user.id);
	$('#change_user_status_modal').modal();
}


function editCategory(categoy){
	$('#category-edit-id').val(categoy.id);
	$('#category-edit-name_ar').val(categoy.name_ar);
	$('#category-edit-name_en').val(categoy.name_en);
	$('#category-edit-status').val(categoy.active);
	$('#edit_categoy_modal').modal();
}
function addCategory(){
	$('#add_categoy_modal').modal();
}
//-------------------------------------


function deleteSlider(slider_id){
	if(confirm('هل تريد حذف هذه الصورة؟')){
		$.ajax({
			method:'POST',
			url:delete_slider_url,
			data:{slider_id:slider_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}
}

function editAboutUs(aboutus){
	$('#aboutus-edit-title').val(aboutus.title);
	$('#aboutus-edit-content').val(aboutus.content);

	$('#edit_aboutus_modal').modal();
}
function editIntro(intro){
	$('#intro-edit-title').val(intro.title);
	$('#intro-edit-content').val(intro.content);

	$('#edit_intro_modal').modal();
}

function deleteContactLink(contactLink_id){
	if(confirm('هل تريد حذف رابط التواصل هذا؟')){
		$.ajax({
			method:'POST',
			url:delete_contactlink_url,
			data:{contactLink_id:contactLink_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}
}

function getPrice(id){
		var numVal1 = Number(document.getElementById("price-val-"+id).value);
		var numVal2 = Number(document.getElementById("discount-val-"+id).value) / 100;
		var totalValue = numVal1 - (numVal1 * numVal2)
		document.getElementById("total-val-"+id).value = totalValue.toFixed(2);
}
function getAddPrice(){
		var numVal1 = Number(document.getElementById("price-val-add").value);
		var numVal2 = Number(document.getElementById("discount-val-add").value) / 100;
		var totalValue = numVal1 - (numVal1 * numVal2)
		document.getElementById("total-val-add").value = totalValue.toFixed(2);
}
