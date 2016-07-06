function addTag(tags)
{
	var tagsTxt = $("#tagTextArea").val().replace(/\r\n|\r$/g, "\n");
	var origTags = tagsTxt.split("\n");
	if (tags instanceof Array)
	{
		origTags = origTags.concat(tags);
	}
	else
	{
		origTags.push(tags);
	}

	var outputTags = uniqueAndTrim(origTags);

	$("#tagTextArea").val(outputTags.join("\n"));
}

function checkboxToTag()
{
	var labelTexts = [];
	var labelText = "";
	$(".tagTarget :checked").each(function()
	{
		labelText = $(this).parent("label").text();
		if(labelText.indexOf("その他") < 0)
		{
			labelTexts.push(labelText);
		}
	});

	addTag(labelTexts);
}

function mutualApply()
{
	checkboxToTag();
	tagToCheckbox();
}

function mutualApplyBeforeSubmit()
{
	if ($("#mutualApplyBeforeSubmitChkbx").prop("checked"))
	{
		mutualApply();
	}
	return true;
}

function tagToCheckbox()
{
	var labelText = "";
	var tagText = "";
	var tagLines = $("#tagTextArea").val().split(/\r|\r\n|\n/);

	jQuery.each(tagLines, function(i)
	{
		tagText = trim(this)

		if (tagText == "")
		{
			return true;
		}

		tagText = zenkakuToHankaku(this).toLowerCase();

		$(".tagTarget input:checkbox").each(function()
		{
			labelText = $(this).parent("label").text();
			if(tagText == zenkakuToHankaku(labelText).toLowerCase())
			{
				$(this).prop("checked", true);
				tagLines[i] = labelText;
			}
		});
	});

	$("#tagTextArea").val(tagLines.join("\n"));
}

function uniqueAndTrim(textArray)
{
	var outputArray = [];
	var normalizedText = "";
	var normalizedTextTmp = "";

	jQuery.each(textArray, function(index, value){
		normalizedText = trim(value);

		// 空行は無視
		if (normalizedText == null || normalizedText == "")
		{
			return true;
		}

		// 全角スペースを半角化
		normalizedText = normalizedText.replace(/　/g, " ");

		// 全角英数字記号を半角化
		normalizedTextTmp = zenkakuToHankaku(normalizedText);

		// 重複要素チェック
		for (i=0; i<outputArray.length; i++)
		{
			if (zenkakuToHankaku(outputArray[i]).toLowerCase() == normalizedTextTmp.toLowerCase())
			{
				return true;
			}
		}

		outputArray.push(normalizedText);
	});
	return outputArray;
}

function trim(str)
{
	return str.replace(/^(\n|\r|\t| |　)+|(\n|\r|\t| |　)+$/g, "");
}

function zenkakuToHankaku(str)
{
	return str.replace(/[！-～]/g, function(str){
		return String.fromCharCode(str.charCodeAt(0) - 0xFEE0);
	});
}
