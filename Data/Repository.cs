using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using CapiValidation.Data.Interfaces;
using Microsoft.EntityFrameworkCore;

namespace CapiValidation.Data
{
    public class Repository<T> : IRepository<T> where T : class, IEntityBase
    {
        private readonly DbContext _context;

        public Repository(DbContext context)
            => _context = context;

        public virtual async Task<IEnumerable<T>> ListAsync()
            => await _context.Set<T>().ToListAsync();

        public virtual async Task<IEnumerable<T>> ListAsync(ISpecification<T> spec)
        {
            var queryableResultWithIncludes = spec.Includes.Aggregate(_context.Set<T>().AsQueryable(), (current, include) => current.Include(include));

            var secondaryResult = spec.IncludeStrings.Aggregate(queryableResultWithIncludes, (current, include) => current.Include(include));

            return await secondaryResult.Where(spec.Criteria).ToListAsync();
        }

        public virtual async Task<T> GetByIdAsync(params object[] id)
            => await _context.Set<T>().FindAsync(id);

        public virtual async Task InsertAsync(T entity)
            => await _context.Set<T>().AddAsync(entity);

        public virtual async Task InsertAsync(IEnumerable<T> entities)
            => await _context.Set<T>().AddRangeAsync(entities);

        public virtual void Update(T entity)
            => _context.Set<T>().Update(entity);

        public virtual void Update(IEnumerable<T> entities)
            => _context.Set<T>().UpdateRange(entities);

        public virtual void Delete(T entity)
            => _context.Set<T>().Remove(entity);

        public virtual void Delete(params object[] id)
        {
            var entity = _context.Find<T>(id);
            _context.Set<T>().Remove(entity);
        }

        public virtual void Delete(IEnumerable<T> entities)
            => _context.Set<T>().RemoveRange(entities);
    }
}