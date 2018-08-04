<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb\Filter;

class Text {
    
    const TEMPLATE_LITERAL = '/\${([^)]+?)}/g';
    const KEY = '/^([a-z_][a-z_\d]*)/i';
    const KEY_ACCESS = '/^\.([a-z_][a-z_\d]*)/i';
    const INDEX_ACCESS = '/^\[(\d+)\]/';
    /**
     * imported from https://github.com/bigchaindb/js-utility-belt/
     * @private
     * Formats strings similarly to C's sprintf, with the addition of '${...}' formats.
     *  
     * Makes a first pass replacing '${...}' formats before passing the expanded string and other
     * arguments to sprintf-js. For more information on what sprintf can do, see
     * https://github.com/alexei/sprintf.js.
     *
     * Examples:
     *   formatText('Hi there ${dimi}!', { dimi: 'Dimi' })
     *       => 'Hi there Dimi!'
     *
     *   formatText('${database} is %(status)s', { database: 'BigchainDB', status: 'big' })
     *       => 'BigchainDB is big'
     *
     * Like sprintf-js, string interpolation for keywords and indexes is supported too:
     *   formatText('Berlin is best known for its ${berlin.topKnownFor[0].name}', {
     *       berlin: {
     *           topKnownFor: [{
     *               name: 'Currywurst'
     *           }, ...
     *           ]
     *       }
     *   })
     *       => 'Berlin is best known for its Currywurst'
     */
    public function filter($value) {
        $expandedFormatStr = $value;
        $argv = func_get_args();
        array_shift($argv);

        // Try to replace formats of the form '${...}' if named replacement fields are used
        if ($value && count($argv) === 1 && is_array($argv[0])) {
            $templateSpecObj = $argv[0];

            $expandedFormatStr = preg_replace(self::TEMPLATE_LITERAL, function($match, $replacement) use ($templateSpecObj, $curMatch) {
                $interpolationLeft = $replacement;

                /**
                 * @private
                 * Interpolation algorithm inspired by sprintf-js.
                 *
                 * Goes through the replacement string getting the left-most key or index to interpolate
                 * on each pass. `value` at each step holds the last interpolation result, `curMatch` is
                 * the current property match, and `interpolationLeft` is the portion of the replacement
                 * string still to be interpolated.
                 *
                 * It's useful to note that RegExp.exec() returns with an array holding:
                 *   [0]:  Full string matched
                 *   [1+]: Matching groups
                 *
                 * And that in the regexes defined, the first matching group always corresponds to the
                 * property matched.
                 */
                if (preg_match(self::KEY, $interpolationLeft, $curMatch)) {
                    $value = $templateSpecObj[$curMatch[1]];

                    // Assigning in the conditionals here makes the code less bloated
                    /* eslint-disable no-cond-assign */
                    while (($interpolationLeft = substr($interpolationLeft, count($curMatch[0]))) &&
                        $value != null
                    ) {
                        if (preg_match(self::KEY_ACCESS, $interpolationLeft, $curMatch)) {
                            $value = $value[$curMatch[1]];
                        } else if (preg_match(self::INDEX_ACCESS, $interpolationLeft, $curMatch)) {
                            $value = $value[$curMatch[1]];
                        } else {
                            break;
                        }
                    }
                    /* eslint-enable no-cond-assign */
                }

                // If there's anything left to interpolate by the end then we've failed to interpolate
                // the entire replacement string.
                if (count($interpolationLeft)) {
                    throw new SyntaxError(`[formatText] failed to parse named argument key: ${replacement}`);
                }

                return $value;
            });
        }
        array_unshift($argv, $expandedFormatStr);
        
        return call_user_func_array('sprintf', $argv);
    }
}

