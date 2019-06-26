function isChecked(formObj, targetRadioName)
{
	var radio = formObj.elements[targetRadioName];

	for (var i=0; i<radio.length; i++)
	{
		if (radio[i].checked)
		{
			return true;
		}
	}
	return false;
}
