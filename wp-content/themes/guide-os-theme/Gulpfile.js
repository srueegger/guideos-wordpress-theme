const postcss = require("gulp-postcss");
const sourcemaps = require("gulp-sourcemaps");
const cssnano = require("cssnano");
const nested = require("postcss-nested");
const autoprefixer = require("autoprefixer");
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");
const { src, dest, watch, series, parallel } = require("gulp");
const postcssImport = require("postcss-import");

// Pfade zur Verarbeitung
const paths = {
  styles: [
    "blocks/**/*.css", // CSS in blocks
    "assets/css/**/*.css", // CSS in assets/css
  ],
  scripts: [
    "blocks/**/*.js", // JS in blocks
    "assets/js/**/*.js", // JS in assets/js
  ],
};

// CSS minimieren
function css() {
  return (
    src([
      ...paths.styles, // Originale CSS-Dateien aus allen definierten Pfaden
      "!blocks/**/*.min.css", // Minimierte Dateien ignorieren
      "!assets/css/**/*.min.css", // Minimierte Dateien ignorieren
    ])
      .pipe(sourcemaps.init()) // Source Maps starten
      .pipe(postcss([
        postcssImport(),
        nested(),
        autoprefixer(),
        cssnano()
      ])) // CSS verarbeiten
      .pipe(rename({ suffix: ".min" })) // .min hinzufügen
      .pipe(sourcemaps.write(".")) // Source Maps schreiben
      .pipe(dest((file) => file.base)) // Am Original-Ort speichern
  );
}

// JS minimieren
function js() {
  return (
    src([
      ...paths.scripts, // Originale JS-Dateien aus allen definierten Pfaden
      "!blocks/**/*.min.js", // Minimierte Dateien ignorieren
      "!assets/js/**/*.min.js", // Minimierte Dateien ignorieren
    ])
      .pipe(sourcemaps.init()) // Source Maps starten
      .pipe(uglify()) // JS minimieren
      .pipe(rename({ suffix: ".min" })) // .min hinzufügen
      .pipe(sourcemaps.write(".")) // Source Maps schreiben
      .pipe(dest((file) => file.base)) // Am Original-Ort speichern
  );
}

// Watch-Task
function watchFiles() {
  watch(
    [
      ...paths.styles,
      "!blocks/**/*.min.css",
      "!assets/css/**/*.min.css",
    ],
    css
  ); // Keine .min.css beobachten
  watch(
    [
      ...paths.scripts,
      "!blocks/**/*.min.js",
      "!assets/js/**/*.min.js",
    ],
    js
  ); // Keine .min.js beobachten
}

// Standard-Task
const build = series(parallel(css, js), watchFiles);

// Exportierbare Tasks
exports.css = css;
exports.js = js;
exports.watch = watchFiles;
exports.default = build;