<?php
require_once('../src/constants.php');
require_once('../src/utils.php');

function initArray($files, $count)
{
    $items = array();
    for ($i = 0; $i < $count; $i++) {
        $file = $files[$i];
        $item = parseLsOutputInformation($file);

        $size = $item['type'] === 'directory' ? getSize($files, $item['name']) : $item['size'];

        $items[] = array(
            'name' => $item['name'],
            'size' => $size,
            'type' => $item['type'],
            'date' => $item['day'] . ' ' . $item['month'] . ' ' . $item['time']
        );
    }

    return $items;
}

/**
 * Take a string ls information and return it into an array of :
 * <ul>
 *  <li>rights</li>
 *  <li>number</li>
 *  <li>user</li>
 *  <li>group</li>
 *  <li>size</li>
 *  <li>month</li>
 *  <li>day</li>
 *  <li>time</li>
 *  <li>name</li>
 *  <li>type (directory or file)</li>
 * </ul>
 * @param $info string information (ex: drwxrws---   26 ComasR0wa1 48               4096 Feb  2 20:44 Abelssoft.Collection.2014-DVT)
 * @return array
 */
function parseLsOutputInformation($info)
{
    $item = array();
    $chunks = preg_split("/\s+/", $info);
    list($item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], $item['month'], $item['day'], $item['time']) = $chunks;
    $item['type'] = $chunks[0]{0} === 'd' ? 'directory' : 'file';
    array_splice($chunks, 0, 8);
    $item['name'] = implode(' ', $chunks);

    return $item;
}

function getSize($filesRawList, $file)
{
    if (in_array('./' . $file . ':', $filesRawList)) {
        $index = array_search('./' . $file . ':', $filesRawList);
        $size = 0;

        if (!empty($filesRawList[$index + 2])) {
            $f = $filesRawList[$index + 2];
            $offset = 2;
            while ($f != '' && ($index + $offset) < count($filesRawList)) {
                $item = parseLsOutputInformation($f);
                if ($item['type'] === 'directory') {
                    $s = getSize($filesRawList, $file . '/' . $item['name']);
                    $size += $s;
                } else {
                    $size += intval($item['size']);
                }
                $offset++;
                $f = $filesRawList[$index + $offset];
            }
        }

        return $size;
    } else {
        return -1;
    }
}

$ftp = createFTPConnection();
if ($ftp) {
    $rawList = ftp_rawlist($ftp, '.', true);
    $totalFiles = count(ftp_nlist($ftp, '.'));

    $output = initArray($rawList, $totalFiles);

    // write content on file
    file_put_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE, json_encode($output));

    ftp_close($ftp);

    // Update last update file
    file_put_contents(TEMP_DIR . LAST_UPDATE_FILE, round(microtime(true) * 1000));
}