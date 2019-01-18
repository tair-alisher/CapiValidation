using System;

namespace CapiValidation.Models.Pagination
{
    public class PageViewModel
    {
        public int PageNumber { get; private set; }
        public int TotalPages { get; private set; }

        public PageViewModel(int count, int pageNumber, int pageSize)
        {
            PageNumber = pageNumber;
            TotalPages = (int)Math.Ceiling(count / (double)pageSize);
        }

        public bool HasPreviousPage { get => (PageNumber > 1); }
        public bool HasNextPage { get => (PageNumber < TotalPages); }
    }
}