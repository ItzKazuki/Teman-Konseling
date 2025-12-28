export default function base64ToFile(base64: string, filename: string): File {
  if (!base64 || typeof base64 !== "string") {
    throw new Error("Invalid base64 input");
  }

  const parts = base64.split(",");
  const header = parts[0];
  const data = parts[1];

  if (!header || !data) {
    throw new Error("Invalid base64 data");
  }

  const mimeMatch = header.match(/:(.*?);/);
  if (!mimeMatch || !mimeMatch[1]) {
    throw new Error("Invalid MIME type in base64 string");
  }

  const mime = mimeMatch[1];

  const binary = atob(data);
  const len = binary.length;
  const u8arr = new Uint8Array(len);

  for (let i = 0; i < len; i++) {
    u8arr[i] = binary.charCodeAt(i);
  }

  return new File([u8arr], filename, { type: mime });
}
