<?php
require_once('../src/constants.php');
require_once('../src/utils.php');

function initArray($filesRawList, $count)
{
    $items = array();
    for ($i = 0; $i < $count; $i++) {
        $file = $filesRawList[$i];
        $item = parseLsOutputInformation($file);

        $children = array();
        if ($item['type'] === 'directory') {
            $size = getSize($filesRawList, $item['name']);
            // Find children
            $children = findChildren($filesRawList, $item['name']);
        } else {
            $size = $item['size'];
        }

        $items[$item['name']] = array(
            'name' => $item['name'],
            'size' => strval($size),
            'type' => $item['type'],
            'children' => $children,
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

function findChildren($filesRawList, $file)
{
    $children = array();
    if (in_array('./' . $file . ':', $filesRawList)) {
        $index = array_search('./' . $file . ':', $filesRawList);

        if (!empty($filesRawList[$index + 2])) {
            $f = $filesRawList[$index + 2];
            $offset = 2;
            while ($f != '' && ($index + $offset) < count($filesRawList)) {
                $item = parseLsOutputInformation($f);
                $itemChildren = array();
                if ($item['type'] === 'directory') {
                    $size = getSize($filesRawList, $file . '/' . $item['name']);
                    // Find children
                    $itemChildren = findChildren($filesRawList, $file . '/' . $item['name']);
                } else {
                    $size = $item['size'];
                }
                $children[$item['name']] = array(
                    'name' => $item['name'],
                    'size' => $size,
                    'type' => $item['type'],
                    'children' => $itemChildren,
                    'date' => $item['day'] . ' ' . $item['month'] . ' ' . $item['time']
                );
                $offset++;
                $f = $filesRawList[$index + $offset];
            }
        }
    }

    return $children;
}

function getSize($filesRawList, $file)
{
    if (in_array('./' . $file . ':', $filesRawList)) {
        $index = array_search('./' . $file . ':', $filesRawList);
        (float) $size = 0;

        if (!empty($filesRawList[$index + 2])) {
            $f = $filesRawList[$index + 2];
            $offset = 2;
            while ($f != '' && ($index + $offset) < count($filesRawList)) {
                $item = parseLsOutputInformation($f);
                if ($item['type'] === 'directory') {
                    $s = getSize($filesRawList, $file . '/' . $item['name']);
                    $size += $s;
                } else {
                    $size += floatval($item['size']);
                }
                $offset++;
                $f = $filesRawList[$index + $offset];
            }
        }

        return $size;
    } else {
        return 0;
    }
}

$ftp = createFTPConnection();
if ($ftp) {
    $rawList = ftp_rawlist($ftp, '.', true);
    $totalFiles = count(ftp_nlist($ftp, '.'));

    $output = initArray($rawList, $totalFiles);

    // write content on file
    file_put_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE, json_encode($output));
    chmod(TEMP_DIR . SEEDBOX_DETAILS_FILE, 0600);

    ftp_close($ftp);

    // Update last update file
    file_put_contents(TEMP_DIR . LAST_UPDATE_FILE, round(microtime(true)));
    chmod(TEMP_DIR . LAST_UPDATE_FILE, 0600);
}