
export default function usePrinter() {
    
    const print = (portrait = true) => {
        var css = '@page { size: ' + (portrait ? 'portrait' : 'landscape') + '; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style')
    
        style.type = 'text/css'
        style.media = 'print'
    
        if (style.styleSheet){
            style.styleSheet.cssText = css
        } else {
            style.appendChild(document.createTextNode(css))
        }
    
        head.appendChild(style)
    
        window.print()
    }

    return {
        print
    }
}