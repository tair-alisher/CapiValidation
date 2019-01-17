using System.Collections.Generic;
using System.Threading.Tasks;
using CapiValidation.Data.Entities;
using CapiValidation.Data.Interfaces;

namespace CapiValidation.Services.Interfaces
{
    public interface IReadableService<T> where T : class, IEntityBase
    {
        Task<T> GetByIdAsync(params object[] id);
        Task<IEnumerable<T>> GetAllAsync();
    }
}