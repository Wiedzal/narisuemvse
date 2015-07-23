function translit(str, lower)
{
	// Символ, на который будут заменяться все спецсимволы
	var space = '-'; 
	// Берем значение из нужного поля и переводим в нижний регистр
	
	if(lower) str = str.toLowerCase();
		 
	// Массив для транслитерации
	var transl = {
		'а': 	'a',
		'б': 	'b',
		'в': 	'v',
		'г': 	'g',
		'д': 	'd',
		'е': 	'e',
		'ё': 	'e',
		'ж': 	'zh', 
		'з': 	'z',
		'и': 	'i',
		'й': 	'j',
		'к': 	'k',
		'л': 	'l',
		'м': 	'm',
		'н': 	'n',
		'о': 	'o',
		'п': 	'p',
		'р': 	'r',
		'с': 	's',
		'т': 	't',
		'у': 	'u',
		'ф': 	'f',
		'х': 	'h',
		'ц': 	'c',
		'ч': 	'ch',
		'ш': 	'sh',
		'щ': 	'sh',
		'ъ': 	'',
		'ы': 	'y',
		'ь': 	'',
		'э': 	'e',
		'ю': 	'yu',
		'я': 	'ya',
		'А': 	'A',
		'Б': 	'B',
		'В': 	'V',
		'Г': 	'G',
		'Д': 	'D',
		'Е': 	'E',
		'Ё': 	'E',
		'Ж': 	'ZH', 
		'З': 	'Z',
		'И': 	'I',
		'Й': 	'J',
		'К': 	'K',
		'Л': 	'L',
		'М': 	'M',
		'Н': 	'N',
		'О': 	'O',
		'П': 	'P',
		'Р': 	'R',
		'С': 	'S',
		'Т': 	'T',
		'У': 	'U',
		'Ф': 	'F',
		'Х': 	'H',
		'Ц': 	'C',
		'Ч': 	'CH',
		'Ш': 	'SH',
		'Щ': 	'SH',
		'Ъ': 	'',
		'Ы': 	'Y',
		'Ь': 	'',
		'Э': 	'E',
		'Ю': 	'YU',
		'Я': 	'YA',
		' ': 	space,
		'_': 	space,
		'`': 	space,
		'~': 	space,
		'!': 	space,
		'@': 	space,
		'#': 	space,
		'$': 	space,
		'%': 	space,
		'^': 	space,
		'&': 	space,
		'*': 	space,
		'(': 	space,
		')': 	space,
		'-': 	space,
		'\=': 	space,
		'+': 	space,
		'[': 	space,
		']': 	space,
		'\\': 	space,
		'|': 	space,
		'/': 	space,
		'.': 	space,
		',': 	space,
		'{': 	space,
		'}': 	space,
		'\'': 	space,
		'"': 	space,
		';': 	space,
		':': 	space,
		'?': 	space,
		'<': 	space,
		'>': 	space,
		'№':	space
	}

	var result = '';
	var curent_sim = '';
					
	for(i=0; i < str.length; i++) {
		// Если символ найден в массиве то меняем его
		if(transl[str[i]] != undefined) {
			 if(curent_sim != transl[str[i]] || curent_sim != space){
				 result += transl[str[i]];
				 curent_sim = transl[str[i]];
															}                                                                             
		}
		// Если нет, то оставляем так как есть
		else {
			result += str[i];
			curent_sim = str[i];
		}                              
	}          
					
	result = TrimStr(result);               
					
	// Выводим результат 
	return result;
    
}

function TrimStr(s) {
    s = s.replace(/^-/, '');
    return s.replace(/-$/, '');
}

$(document).ready(function(){
	$('.translit').click(function(){
		t = translit($('.translit-string').val(), true);
		$('.translit-input').val(t);
		$('.translit-text').text(t);
	})
})