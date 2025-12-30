export default function(status: string): string {
  switch (status) {
    case 'draft':
      return 'bg-gray-100 text-gray-800 border-gray-200';
    case 'published':
      return 'bg-green-100 text-green-800 border-green-200';
    case 'archived':
      return 'bg-yellow-100 text-yellow-800 border-yellow-200';
    default:
      return 'bg-gray-100 text-gray-800 border-gray-200';
  }
}