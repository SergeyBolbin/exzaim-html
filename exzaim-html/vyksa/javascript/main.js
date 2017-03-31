/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function sendMail() {
    var name1 = document.getElementById('name1').value;
    var name2 = document.getElementById('name2').value;
    var name3 = document.getElementById('name3').value;

    var phone = document.getElementById('phone').value;

    var subj = name1+ " " + name2 + " " + name3 + "(" + phone + ")Займ";
    var link = "mailto:kolobneva_ea@mail.ru"
             + "?cc=kolobneva_ea@mail.ru"
             + "&subject=" + subj
             + "&body=" + document.getElementById('text').value;

    window.location.href = link;
}
