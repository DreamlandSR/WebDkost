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
    proxy: {
        '/': { // Aturan proxy umum
            target: process.env.VITE_APP_URL || 'http://Project.test', // Sesuaikan dengan URL backend Anda
            changeOrigin: true,
            bypass: (req, res, proxyOptions) => {
                const viteAssetPatterns = [
                    /^\/@vite\//,
                    /^\/@id\//,
                    /^\/__inspect\//,
                    /^\/node_modules\//,
                    /^\/resources\//,
                    /\.(js|css|json|png|jpe?g|gif|svg|ico|webp|woff2?|ttf|eot)$/
                ];
                if (viteAssetPatterns.some(pattern => pattern.test(req.url))) {
                    return req.url;
                }
                return null;
            }
        }
    }
}
});
