export const useFile = () => {
  const api = useApi();

  const getAllFiles = (userId: string) =>
    useAsyncData(`files-user-${userId}`, () => api.fetch("/files"));

  const uploadFileForm = async (payload: FormData): Promise<string | null> => {
    try {
      const res = await api.post<FileAsset>("/files", payload);
      if (res.status) {
        return res.data.id;
      }
      return null;
    } catch (err: any) {
      throw err;
    }
  };

  const uploadAsset = async (
    file: File | null,
    fileLocation: string,
    visibility?: FileVisibilityType
  ) => {
    if (!file) return null;

    const formData = new FormData();
    formData.append("file", file);
    formData.append("file_location", fileLocation);
    formData.append("visibility", visibility ?? "local");

    return await uploadFileForm(formData);
  };

  return { getAllFiles, uploadAsset };
};
