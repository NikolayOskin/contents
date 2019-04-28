<?php

namespace NikolayOskin\Contents;

class ContentsFromHeadersGenerator
{
    /**
     * Generating multi-dimensional array. For example if there are some <h3>
     * tags after <h2> tag, then this <h2> tag has 'childs' array.
     * @param array $headers
     * @return array
     */
    public function generateFromHeaders(array $headers)
    {
        $newArr = [];
        $level1 = 1;
        for ($i = 0; $i <= count($headers); $i++) {
            $currentLevel = $headers[$i]['level'];
            if ($currentLevel === 0) {
                $newArr[] = $headers[$i];
            } elseif ($currentLevel === 1) {
                $newArr[$i-$level1]['childs'][] = $headers[$i];
            } elseif ($currentLevel === 2) {
                $childs =& $newArr[$i-$level1]['childs'];
                $childs[count($childs)-1]['childs'][] = $headers[$i];
            } elseif ($currentLevel === 3) {
                $childs =& $newArr[$i-$level1]['childs'];
                $count = count($childs[count($childs)-1]['childs']);
                $childs[count($childs)-1]['childs'][$count-1]['childs'][] = $headers[$i];
            } elseif ($currentLevel === 4) {
                $childs =& $newArr[$i-$level1]['childs'];
                $subChilds =& $childs[count($childs)-1]['childs'];
                $subChilds1 =& $subChilds[count($subChilds)-1]['childs'];
                $subChilds1[count($subChilds1) - 1]['childs'][] = $headers[$i];
            }
            $level1 = $currentLevel > 0 ? $level1 + 1 : 1;
        }
        return $newArr;
    }
}
