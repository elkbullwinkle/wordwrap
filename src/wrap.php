<?php

/**
 * Wraps a string to a given number of characters
 *
 * @param string $string Given string
 * @param int $length Chunk length
 * @return string the given string wrapped at the specified length
 * @throws Exception if the provided length is incorrect
 */
function wrap(string $string, int $length)
{
    if ($length <= 0) {
        throw new Exception("Can not wrap a string into chunks with length: {$length}");
    }

    if (strlen($string) <= $length) {
        return $string;
    }

    $chunks = [];

    while (strlen($string) > 0) {
        $chunk = substr($string, 0, $length);
        $endOfString = rtrim($string) == rtrim($chunk);
        $beginningOfString = trim(implode('', $chunks)) == '';

        //Preserve an empty chunk at the beginning or the end of the string
        if (preg_match("/^\s{{$length}}$/", $chunk) && ($endOfString || $beginningOfString)) {
            $chunks[] = $chunk;

            $string = substr($string, strlen($chunk));
            continue;
        }

        //Checking for the end of the string to preserve trailing spaces
        if ($endOfString) {
            //Removing leading spaces in this chunk if it has words
            if (trim($chunk) != '') {
                $chunk = ltrim($chunk);
            }

            //If the string has more trailing spaces and needs to be wrapped again
            //If all the spaces fit in this chunk just pushing it to chunks array and breaking the loop
            if (strlen($string) > $length) {
                $chunks[] = $chunk;
                $string = substr($string, strlen($chunk));

                continue;
            } else {
                $chunks[] = $chunk;
                break;
            }
        }

        $preserveLeadingSpaces = trim(implode('', $chunks)) == '';

        if (!$preserveLeadingSpaces) {
            $string = ltrim($string);
        }

        $chunk = substr($string, 0, $length);
        if ($breakPos = strpos($chunk, "\n")) {
            $chunk = substr($chunk, 0, $breakPos);
            $chunks[] = $chunk;

            $string = substr($string, strlen($chunk) + 1);

            continue;
        }

        //If there is no following character we can treat this chunk like it doesn't end in a middle of a word
        $followingCharacter = isset($string[strlen($chunk)]) ? $string[strlen($chunk)] : ' ';

        if (!in_array($followingCharacter, [' ', "\n"])) {
            if (!strrpos(ltrim($chunk), ' ')) {
                $chunk = substr($chunk, 0, $length);
            } else {
                $chunk = substr($chunk, 0, strrpos($chunk, ' ') + 1);
            }
        }

        $chunks[] = $preserveLeadingSpaces ? rtrim($chunk) : trim($chunk);

        $string = substr($string, strlen($chunk));
    }

    return implode("\n", $chunks);
}
