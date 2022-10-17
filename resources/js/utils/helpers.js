export function useHelpers() {
    function integerArrayToRanges(array) {
        array.sort(function(a, b) {
            return a - b
        })
        
        for (var ranges = [], rend, i = 0; i < array.length;) {
            ranges.push ((rend = array[i]) + ((function (rstart) {
                while (++rend === array[++i]);
                return --rend === rstart;
            })(rend) ? '' : '-' + rend)); 
        }
        return ranges.join(",")
    }

    function rangesToIntegerArray(str) { // accepts strings
        const ranges = str.replace(/\s/g, '').split(",")
        let values = []

        ranges.forEach(range => {
            if(range.length > 0) {
                const splitted = range.split("-")
                if(splitted.length == 1) {
                    values.push(splitted[0])
                } else if(splitted.length == 2) {
                    let start = parseInt(splitted[0])
                    let end = parseInt(splitted[1])

                    if(start <= end) {
                        values = [...values, ...[...Array(end - start + 1).keys()].map(x => x + start)]
                    }
                }
            }
        })

        return values
    }

    return {
        integerArrayToRanges,
        rangesToIntegerArray
    }
}