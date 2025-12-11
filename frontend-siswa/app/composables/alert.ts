export const useAlert = defineStore("modal", {
  state: () => ({
    show: false,
    type: "info" as AlertType,
    message: "",
    autoClose: false,
    duration: 3000,
    actions: shallowRef<
      {
        label: string;
        color?: string;
      }[]
    >([]),
    _resolver: null as null | ((result: boolean) => void),
    _timer: null as null | ReturnType<typeof setTimeout>, // Timer untuk autoClose
  }),
  actions: {
    _setupAlert(type: AlertType, message: string, autoClose: boolean, duration: number, actions: typeof this.actions = []) {
      // Clear timer lama jika ada
      if (this._timer) {
        clearTimeout(this._timer);
        this._timer = null;
      }

      this.type = type;
      this.message = message;
      this.actions = actions;
      this.autoClose = autoClose;
      this.duration = duration;
      this.show = true;

      if (autoClose) {
        this._timer = setTimeout(() => this.close(), duration);
      }
    },

    info(message: string, autoClose = true, duration = 3000) {
      this._setupAlert("info", message, autoClose, duration);
    },

    success(message: string, autoClose = true, duration = 3000) {
      this._setupAlert("success", message, autoClose, duration);
    },

    warning(message: string, autoClose = true, duration = 3000) {
      this._setupAlert("warning", message, autoClose, duration);
    },
    
    error(message: string, autoClose = false, duration = 4000) {
      // Error biasanya tidak ditutup otomatis agar user melihat pesannya
      this._setupAlert("error", message, autoClose, duration); 
    },

    confirm(message: string): Promise<boolean> {
      // Selalu nonaktifkan autoClose untuk confirm
      this._setupAlert("info", message, false, 0, [
        { label: "Batal", color: "bg-gray-500 hover:bg-gray-600 text-white" },
        { label: "Ya", color: "bg-blue-800 hover:bg-blue-900 text-white" },
      ]);
      
      // Mengubah type menjadi warning/info agar sesuai dengan konteks
      this.type = "warning"; 

      return new Promise((resolve) => {
        this._resolver = resolve;
      });
    },

    resolve(result: boolean) {
      if (this._resolver) {
        this._resolver(result);
        this._resolver = null;
      }
      this.close();
    },

    close() {
      if (this._timer) {
        clearTimeout(this._timer);
        this._timer = null;
      }
      
      this.show = false;
      this.message = "";
      this.actions = [];
      this.type = "info";
    },
  },
});