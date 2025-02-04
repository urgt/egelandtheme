import { defineConfig } from "vite";
import path from "path";

export default defineConfig({
  base: "",

  build: {
    outDir: path.resolve(__dirname, "dist"),

    emptyOutDir: true,

    manifest: false,

    rollupOptions: {
      input: {
        index: path.resolve(__dirname, "assets/js/index.js"),
        like_dislike_rating: path.resolve(
          __dirname,
          "assets/js/like-dislike-rating.js"
        ),
        main: path.resolve(__dirname, "assets/scss/main.scss"),
      },
      output: {
        entryFileNames: "js/[name].js",
        chunkFileNames: "js/[name].js",

        assetFileNames: () => {
          return "css/[name].[ext]";
        },
      },
    },
  },

  server: {},
});
