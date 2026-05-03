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
        host: '0.0.0.0', // Membuka akses ke semua interface jaringan dalam kontainer
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost', // Browser di Windows tetap mengakses via 'localhost'
        },
        watch: {
            usePolling: true, // WAJIB untuk WSL: agar Vite peka terhadap perubahan file
        },
    },
});
