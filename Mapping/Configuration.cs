using System;
using AutoMapper;

namespace CapiValidation.Mapping
{
    public class Configuration
    {
        private static Lazy<IConfigurationProvider> _defaultConfiguration = new Lazy<IConfigurationProvider>(
            () => new MapperConfiguration(config =>
            {
                config.AddProfile(new MappingProfile());
            })
        );

        public static IConfigurationProvider DefautlConfiguration
            => _defaultConfiguration.Value;

        public static IMapper CreateDefaultMapper()
            => new Mapper(DefautlConfiguration);
    }
}