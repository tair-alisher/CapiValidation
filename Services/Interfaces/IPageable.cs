using System.Collections.Generic;
using System.Threading.Tasks;
using CapiValidation.Data.Interfaces;

namespace CapiValidation.Services.Interfaces
{
    public interface IPageable<T> where T : class, IEntityBase
    {
        Task<int> GetItemsAmountAsync();
        Task<IEnumerable<T>> GetPagedListAsync(int page, int pageSize);
    }
}