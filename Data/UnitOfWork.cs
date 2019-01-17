using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using CapiValidation.Data.Interfaces;
using Microsoft.EntityFrameworkCore;

namespace CapiValidation.Data
{
    public class UnitOfWork : IUnitOfWork
    {
        private readonly DbContext _context;
        private readonly IDictionary<string, IRepository> _repositories;
        private readonly IDictionary<string, IPartialRepository> _partialRepositories;

        public UnitOfWork(DbContext context)
        {
            _context = context;
            _repositories = new SortedDictionary<string, IRepository>();
            _partialRepositories = new SortedDictionary<string, IPartialRepository>();
        }

        public IRepository<T> GetRepository<T>() where T : class, IEntityBase
        {
            var typeName = typeof(T).FullName;
            if (!_repositories.ContainsKey(typeName))
                _repositories.Add(typeName, new Repository<T>(_context));
            return (IRepository<T>)_repositories[typeName];
        }

        public IPartialRepository<T> GetPartialRepository<T>() where T : class, IEntityBase
        {
            var typeName = typeof(T).FullName;
            if (!_partialRepositories.ContainsKey(typeName))
                _partialRepositories.Add(typeName, new PartialRepository<T>(_context));
            return (IPartialRepository<T>)_partialRepositories[typeName];
        }

        public void SaveChanges()
            => _context.SaveChanges();

        public Task SaveChangesAsync()
            => _context.SaveChangesAsync();

        private bool disposed = false;
        public virtual void Dispose(bool disposing)
        {
            if (!this.disposed)
            {
                if (disposing)
                _context.Dispose();
                this.disposed = true;
            }
        }

        public void Dispose()
        {
            Dispose(true);
            GC.SuppressFinalize(this);
        }
    }
}