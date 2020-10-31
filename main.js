
// 削除確認ダイアログ
function delete_alert(){
  if(!window.confirm('本当に削除しますか？')){
     return false;
  }else{
    return true;
  }
};
// 確認ダイアログ
function GetReward(){
  if(!window.confirm('ご褒美をGETしますか？')){
    return false;
  }
  window.alert('おめでとうございます。\nご褒美を楽しんでください！');
  return true;
}
// 完了済みリストの表示切り替え
document.getElementById("open_lists").style.display ="none";
function listToggle(){
  let lists = document.getElementById('open_lists');

  if(open_lists.style.display=="block"){
		open_lists.style.display ="none";
	}else{
		open_lists.style.display ="block";
	}
}