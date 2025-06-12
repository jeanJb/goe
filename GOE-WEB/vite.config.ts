import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";

export default defineConfig({
  plugins: [react({
    jsxRuntime: 'classic' // Opcional: ayuda con algunos problemas de renderizado
  })],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
    extensions: ['.js', '.jsx', '.json'] // Añadir extensiones por defecto
  },
  server: {
    port: 3000,
    host: true, // Permite acceso desde la red local
    strictPort: true, // Si el puerto está ocupado, falla en lugar de buscar otro
    proxy: {
      "/api": {
        target: "http://localhost:8000",
        changeOrigin: true,
        secure: false, // Útil para desarrollo con HTTPS auto-firmado
        rewrite: (path) => path.replace(/^\/api/, ""),
        ws: true // Habilita WebSockets si tu backend los usa
      },
    },
    open: true // Abre el navegador automáticamente
  },
  optimizeDeps: {
    include: ['jwt-decode', 'react-hot-toast'] // Fuerza inclusión de dependencias clave
  },
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    sourcemap: true // Útil para debugging
  }
});