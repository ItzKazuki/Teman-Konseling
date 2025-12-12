export default function (
  message: string,
  type: "info" | "warn" | "error" | "log" = "info"
): void {
  if(useRuntimeConfig().public.debug == false || useRuntimeConfig().public.appEnv === "production") return;
  
  const timestamp = new Date().toISOString();

  switch (type) {
    case "info":
      console.info(`[INFO] [${timestamp}]: ${message}`);
      break;
    case "warn":
      console.warn(`[WARN] [${timestamp}]: ${message}`);
      break;
    case "error":
      console.error(`[ERROR] [${timestamp}]: ${message}`);
      break;
    case "log":
      console.log(`[LOG] [${timestamp}]: ${message}`);
  }
}
