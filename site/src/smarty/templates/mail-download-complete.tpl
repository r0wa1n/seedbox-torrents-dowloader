{extends file='mail-layout.tpl'}
{block name=content}
    Your download is complete : <span style="font-style: italic;">{$file}</span>.</br>
    Details :</br>
    <ul>
        <li>Size : {$size}</li>
        <li>Begin at : {$begin}</li>
        <li>Finish at : {$end}</li>
        <li>Duration : {$duration}</li>
        <li>Average : {$average}Mo/s</li>
    </ul>
{/block}