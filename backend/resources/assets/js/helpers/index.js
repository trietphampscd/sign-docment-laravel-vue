export function bytesToSize(bytes) {
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
  if (bytes === 0) return 'n/a'
  const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10)
  if (i === 0) return `${bytes} ${sizes[i]}`
  return `${(bytes / (1024 ** i)).toFixed(1)} ${sizes[i]}`
}

export function convertUrlToFile(value, urlKey) {
  return new File([value[urlKey]], value.name, {
    type: extensionToMimeType(`${value.name.split(".")[1]}`)
  });
}

export function addParamsToBlob(theBlob, file) {
  let b = theBlob;
  //A Blob() is almost a File() - it's just missing the two properties below which we will add
  b.lastModifiedDate = new Date();
  b.name = file.name;
  b.downloadUrl = file.downloadUrl;
  b.id = file.id ? file.id : null;

  //Cast to a File() type
  return theBlob;
}

export function blobToFile(blob) {
  return new File([blob], blob.name, { lastModified: Date.now(), type: blob.type })
}

export function extensionToMimeType(extension) {
  const type = {
    shtml: 'text/html',
    html: 'text/html',
    htm: 'text/html',
    css: 'text/css',
    xml: 'text/xml',
    gif: 'image/gif',
    jpg: 'image/jpeg',
    js: ' application/x-javascript',
    atom: 'application/atom+xml',
    rss: 'application/rss+xml',

    mml: 'text/mathml',
    txt: 'text/plain',
    jad: 'text/vnd.sun.j2me.app-descriptor',
    wml: 'text/vnd.wap.wml',
    htc: 'text/x-component',

    png: 'image/png',
    tiff: 'image/tiff',
    tif: 'image/tiff',
    wbmp: 'image/vnd.wap.wbmp',
    ico: 'image/x-icon',
    jng: 'image/x-jng',
    bmp: 'image/x-ms-bmp',
    svg: 'image/svg+xml',
    webp: 'image/webp',

    ear: 'application/java-archive',
    hqx: 'application/mac-binhex40',
    doc: 'application/msword',
    pdf: 'application/pdf',
    ai: 'application/postscript',
    ps: 'application/postscript',
    eps: 'application/postscript',
    rtf: 'application/rtf',
    xls: 'application/vnd.ms-excel',
    ppt: 'application/vnd.ms-powerpoint',
    wmlc: 'application/vnd.wap.wmlc',
    kml: 'application/vnd.google-earth.kml+xml',
    kmz: 'application/vnd.google-earth.kmz',
    '7z': 'application/x-7z-compressed',
    cco: 'application/x-cocoa',
    jardiff: 'application/x-java-archive-diff',
    jnlp: 'application/x-java-jnlp-file',
    run: 'application/x-makeself',
    pm: 'application/x-perl',
    pl: 'application/x-perl',
    pdb: 'application/x-pilot',
    prc: 'application/x-pilot',
    rar: 'application/x-rar-compressed',
    rpm: 'application/x-redhat-package-manager',
    sea: 'application/x-sea',
    swf: 'application/x-shockwave-flash',
    sit: 'application/x-stuffit',
    tk: 'application/x-tcl',
    tcl: 'application/x-tcl',
    crt: 'application/x-x509-ca-cert',
    pem: 'application/x-x509-ca-cert',
    der: 'application/x-x509-ca-cert',
    xpi: 'application/x-xpinstall',
    xhtml: 'application/xhtml+xml',
    zip: 'application/zip',

    dll: 'application/octet-stream',
    exe: 'application/octet-stream',
    bin: 'application/octet-stream',
    deb: 'application/octet-stream',
    dmg: 'application/octet-stream',
    eot: 'application/octet-stream',
    img: 'application/octet-stream',
    iso: 'application/octet-stream',
    msm: 'application/octet-stream',
    msp: 'application/octet-stream',
    msi: 'application/octet-stream',

    kar: 'audio/midi',
    midi: 'audio/midi',
    mid: 'audio/midi',
    mp3: 'audio/mpeg',
    ogg: 'audio/ogg',
    ra: 'audio/x-realaudio',

    '3gp': 'video/3gpp',
    '3gpp': 'video/3gpp',
    mpg: 'video/mpeg',
    mpeg: 'video/mpeg',
    mov: 'video/quicktime',
    flv: 'video/x-flv',
    mng: 'video/x-mng',
    asf: 'video/x-ms-asf',
    asx: 'video/x-ms-asf',
    wmv: 'video/x-ms-wmv',
    avi: 'video/x-msvideo',
    mp4: 'video/mp4',
    m4v: 'video/mp4',
  }
  return type[extension] || '';
}

export function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

export const userListDefaultColors = ["#ee9964", "#60ccd8", "#d5c45c", "#ff9797", "#adffcb", "#d8f962", "#f9baff"] || getRandomColor();
