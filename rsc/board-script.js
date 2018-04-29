var embed_check;

function moveTimer(){
	var time_node = document.getElementById("time");
	var time = Math.floor(Date.now() / 1000);
	var seconds = 60 - (time % 900) % 60;
	var minutes = 14 - Math.floor((time % 900) / 60);
	if(seconds == 60) {
		seconds = 0;
		minutes++;
	}
	if(seconds < 10)
		time_node.textContent = "" + (minutes  + ":0" + seconds);
	else
		time_node.textContent = "" + (minutes  + ":" + seconds);
}

function setCatalog(){
	document.cookie = "display=catalog"
	var threads = document.getElementsByClassName("thread-container-list");
	var item_len = threads.length ;
	for (var thread = item_len - 1 ; thread >= 0; thread--){
		threads[thread].className = "thread-container";
	}
	var rows = document.getElementsByClassName("row-container-list");
	var item_len = rows.length ;
	for (var row = item_len - 1 ; row >= 0; row--){
		rows[row].className = "row-container";
	}
	var contents = document.getElementsByClassName("thread-contents-list");
	var item_len = contents.length ;
	for (var content = item_len - 1 ; content >= 0; content--){
		contents[content].className = "thread-contents";
	}
	var lists = document.getElementsByClassName("interaction-item-list");
	var item_len = lists.length ;
	for (var list = item_len - 1 ; list >= 0; list--){
		lists[list].className = "interaction-item";
	}
	var details = document.getElementsByClassName("details-list");
	var item_len = details.length ;
	for (var detail = item_len - 1 ; detail >= 0; detail--){
		details[detail].className = "details";
	}
	var images = document.getElementsByClassName("thread-image-list");
	var item_len = images.length ;
	for (var image = item_len - 1 ; image >= 0; image--){
		images[image].className = "thread-image";
	}

	var texts = document.getElementsByClassName("thread-text-list");
	var item_len = texts.length ;
	for (var text = item_len - 1 ; text >= 0; text--){
		texts[text].className = "thread-text";
	}
	console.log(embed_check);
	if(embed_check){
		var embeded = document.getElementsByClassName("thread-container");
		var item_len = embeded.length ;
		console.log(embeded);
		for (var embed = item_len - 1 ; embed >= 0; embed--){
			embeded[embed].className += " embeded ";
		}
	}
}
function setList(){
	document.cookie = "display=list"
	var threads = document.getElementsByClassName("thread-container");
	var item_len = threads.length ;
	for (var thread = item_len - 1 ; thread >= 0 ; thread--){
		threads[thread].className = "thread-container-list";
	}
	var rows = document.getElementsByClassName("row-container");
	var item_len = rows.length ;
	for (var row = item_len - 1 ; row >= 0; row--){
		rows[row].className = "row-container-list";
	}
	var contents = document.getElementsByClassName("thread-contents");
	var item_len = contents.length ;
	for (var content = item_len - 1 ; content >= 0; content--){
		contents[content].className = "thread-contents-list";
	}
	var lists = document.getElementsByClassName("interaction-item");
	var item_len = lists.length ;
	for (var list = item_len - 1 ; list >= 0; list--){
		lists[list].className = "interaction-item-list";
	}
	var details = document.getElementsByClassName("details");
	var item_len = details.length ;
	for (var detail = item_len - 1 ; detail >= 0; detail--){
		details[detail].className = "details-list";
	}
	var images = document.getElementsByClassName("thread-image");
	var item_len = images.length ;
	for (var image = item_len - 1 ; image >= 0; image--){
		images[image].className = "thread-image-list";
	}
	var texts = document.getElementsByClassName("thread-text");
	var item_len = texts.length ;
	for (var text = item_len - 1 ; text >= 0; text--){
		texts[text].className = "thread-text-list";
	}
	var embeded = document.getElementsByClassName("embeded");
	var embed = embeded.length ;
	for (var embed = item_len - 1 ; embed >= 0; embed--){
		embeded[embed].className = "embed-list";
	}
	console.log(embed_check);
	if(embed_check){
		var embeded = document.getElementsByClassName("thread-container-list");
		var item_len = embeded.length ;
		console.log(embeded);
		for (var embed = item_len - 1 ; embed >= 0; embed--){
			embeded[embed].className += " embeded  max";
		}
	}
}

//https://www.w3schools.com/js/js_cookies.asp
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

moveTimer();
setInterval(moveTimer, 1000);
console.log("#");
if(document.getElementById("list-link") != null){
	document.getElementById("list-link").addEventListener("click", setList);
	document.getElementById("catalog-link").addEventListener("click", setCatalog);	
}
embed_check  = getCookie("page-style") != "native";
console.log(embed_check);