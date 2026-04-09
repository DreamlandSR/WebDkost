import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";
import inject from "@rollup/plugin-inject";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        inject({
            $: "jquery",
            jQuery: "jquery",
            include: ["**/*.js"], // Hanya proses file JS
            exclude: [
                "**/*.css", // Exclude file CSS
                "**/node_modules/**",
            ],
        }),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
    optimizeDeps: {
        include: ["jquery", "bootstrap"],
    },
    build: {
        rollupOptions: {
        },
    },
    assetsInclude: ["**/*.woff", "**/*.woff2", "**/*.ttf", "**/*.eot"],
    css: {
        url: true,
    },
    server: {
    hmr: {
        host: 'localhost',
    },
}
});
