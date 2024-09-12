import tkinter as tk
from tkinter import messagebox

# Función que se llama cuando se hace clic en el botón
def on_button_click():
    messagebox.showinfo("Información", "¡Hola! Has hecho clic en el botón.")

# Crear la ventana principal
root = tk.Tk()
root.title("Mi Interfaz Gráfica")

# Crear un botón y agregarlo a la ventana
button = tk.Button(root, text="Haz clic en mí", command=on_button_click)
button.pack(pady=20)

# Iniciar el bucle principal de la aplicación
root.mainloop()
