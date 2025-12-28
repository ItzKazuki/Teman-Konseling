type FileVisibilityType = "public" | "local";

interface FileAsset {
  id: string;
  uploadedBy: string;
  fileName: string;
  filePath: string;
  mimeType: string;
  size: number;
  visibility: FileVisibilityType;
  uploadedAt: string;
}